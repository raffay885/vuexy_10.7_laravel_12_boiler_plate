<?php

namespace App\Repositories;

use App\Interfaces\EstimateRepositoryInterface;
use App\Models\Estimate;
use App\Traits\Syncro;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\SyncroProductRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class EstimateRepository implements EstimateRepositoryInterface{

	use Syncro;
	protected $userRepository;
	protected $syncroProductRepository;

	public function __construct(
		UserRepositoryInterface $userRepository,
		SyncroProductRepositoryInterface $syncroProductRepository
	){
		$this->userRepository = $userRepository;
		$this->syncroProductRepository = $syncroProductRepository;
	}

	public function getDataTable(array $filters = []){
		return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return Estimate::with(['customer', 'syncroProduct'])->where([ ...$filters ])->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		return Estimate::where([ ...$filters ])->orderBy('id', 'desc')->first();
	}

	public function count(array $filters = []){
		return Estimate::where([ ...$filters ])->count();
	}

	public function create(array $data){
		try{
			$is_annual = 1;
			$customer = $this->userRepository->findOne(['id' => $data['customer_id']]);
			if(!$customer){
				return ['status' => false, 'message' => 'Customer not found', 'statusCode' => 404];
			}

			$syncroProduct = $this->syncroProductRepository->findOne(['id' => $data['syncro_product_id']]);
			if(!$syncroProduct){
				return ['status' => false, 'message' => 'Product not found', 'statusCode' => 404];
			}
			
			$syncroProductResponse = $this->syncroGet('products/' . $syncroProduct->syncro_product_id);
			if(!$syncroProductResponse || !isset($syncroProductResponse['product'])){
				return ['status' => false, 'message' => 'Product not found', 'statusCode' => 404];
			}

			$productPrice = $syncroProductResponse['product']['price_retail'];
			$assetAmountPerMonth = $productPrice * $data['quantity'];
			$invoiceTotal = $assetAmountPerMonth * 12;
			
			// Monthly customer will not be prorated
			if($customer->billing_type == 'annual'){
				if(!isset($data['is_annual'])){
					$annualEstimate = $this->findOne(['customer_id' => $data['customer_id'], 'status' => 'Approved', 'is_annual' => 1]);
					if($annualEstimate){
						$approvalDate = Carbon::parse($annualEstimate->approved_at)->startOfMonth();
						$currentDate = now()->startOfMonth();
						$monthsPassed = $approvalDate->diffInMonths($currentDate);
						$remainingMonths = max(0, 12 - $monthsPassed);
	
						$invoiceTotal = $assetAmountPerMonth * $remainingMonths;
						$is_annual = 0;
					}
				}else{
					$this->findOne(['customer_id' => $data['customer_id'], 'status' => 'Approved', 'is_annual' => 1])->update(['is_annual' => 0]);
				}
			}else{
				$invoiceTotal = $assetAmountPerMonth;
				$annualEstimate = $this->findOne(['customer_id' => $data['customer_id'], 'status' => 'Approved', 'is_annual' => 1]);
				if($annualEstimate){
					$annualEstimate->update(['is_annual' => 0]);
				}

				$is_annual = 1;
			}

			$syncroResponse = $this->syncroPost('estimates', [
				'date' => now()->format('Y-m-d'),
				'customer_id' => $customer->syncro_customer_id,
				'status' => 'Fresh',
				'line_items' => [
					[
						'product_id' => $data['syncro_product_id'],
						'quantity' => $data['quantity'],
					],
				],
				'note' => $data['note'],
			]);

			// Dummy 
			// $syncroResponse['estimate'] = [
			// 	'number' => 'EST-' . rand(10000000, 99999999),
			// 	'subtotal' => $invoiceTotal,
			// 	'total' => $invoiceTotal,
			// 	'tax' => 0,
			// 	'status' => 'Fresh',
			// 	'id' => 'EST-' . rand(10000000, 99999999),
			// ];

			if($syncroResponse && isset($syncroResponse['estimate'])){
				Estimate::create([
					'customer_id' => $data['customer_id'],
					'syncro_estimate_id' => $syncroResponse['estimate']['id'],
					'syncro_estimate_number' => $syncroResponse['estimate']['number'],
					'syncro_product_id' => $data['syncro_product_id'],
					'quantity' => $data['quantity'],
					'syncro_estimate_subtotal' => $syncroResponse['estimate']['subtotal'],
					'syncro_estimate_total' => $syncroResponse['estimate']['total'],
					'syncro_estimate_tax' => $syncroResponse['estimate']['tax'],
					'status' => $syncroResponse['estimate']['status'],
					'note' => $data['note'],
					'is_annual' => $is_annual,
					'invoice_total' => $invoiceTotal,
				]);

				return ['status' => true, 'message' => 'Estimate created successfully', 'statusCode' => 200];
			}

			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

}