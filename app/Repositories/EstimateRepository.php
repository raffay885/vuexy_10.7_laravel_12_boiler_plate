<?php

namespace App\Repositories;

use App\Interfaces\EstimateRepositoryInterface;
use App\Models\Estimate;
use App\Traits\Syncro;
use App\Interfaces\UserRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class EstimateRepository implements EstimateRepositoryInterface{

	use Syncro;
	protected $userRepository;

	public function __construct(UserRepositoryInterface $userRepository){
		$this->userRepository = $userRepository;
	}

	public function getDataTable(array $filters = []){
		return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return Estimate::with('customer')->where([ ...$filters ])->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		return Estimate::where([ ...$filters ])->orderBy('id', 'desc')->first();
	}

	public function count(array $filters = []){
		return Estimate::where([ ...$filters ])->count();
	}

	public function create(array $data){
		try{
			$customer = $this->userRepository->findOne(['id' => $data['customer_id']]);
			if(!$customer){
				return ['status' => false, 'message' => 'Customer not found', 'statusCode' => 404];
			}

			$syncroProductResponse = $this->syncroGet('products/' . $data['syncro_product_id']);
			if(!$syncroProductResponse || !isset($syncroProductResponse['product'])){
				return ['status' => false, 'message' => 'Product not found', 'statusCode' => 404];
			}

			// $productPrice = $syncroProductResponse['product']['price_retail'];
			$productPrice = 120;
			$assetAmountPerMonth = $productPrice * $data['quantity'];
			$invoiceTotal = $assetAmountPerMonth * 12;

			if(!isset($data['is_annual'])){
				$annualEstimate = $this->findOne(['customer_id' => $data['customer_id'], 'status' => 'Approved', 'is_annual' => 1]);
				if($annualEstimate){
					$approvalDate = Carbon::parse($annualEstimate->approved_at)->startOfMonth();
					$currentDate = now()->startOfMonth();
					$monthsPassed = $approvalDate->diffInMonths($currentDate);
					$remainingMonths = max(0, 12 - $monthsPassed);

					$invoiceTotal = $assetAmountPerMonth * $remainingMonths;
				}
			}else{
				$this->findOne(['customer_id' => $data['customer_id'], 'status' => 'Approved', 'is_annual' => 1])->update(['is_annual' => 0]);
			}
			
			// $syncroResponse = $this->syncroPost('estimates', [
			// 	'date' => now()->format('Y-m-d'),
			// 	'customer_id' => $customer->syncro_customer_id,
			// 	'status' => 'Fresh',
			// 	'line_items' => [
			// 		[
			// 			'product_id' => $data['syncro_product_id'],
			// 			'quantity' => $data['quantity'],
			// 		],
			// 	],
			// 	'note' => $data['note'],
			// ]);

			$syncroResponse['estimate'] = [
				'number' => 'EST-' . rand(10000000, 99999999),
				'subtotal' => $invoiceTotal,
				'total' => $invoiceTotal,
				'tax' => 0,
				'status' => 'Fresh',
				'id' => 'EST-' . rand(10000000, 99999999),
			];

			if($syncroResponse && isset($syncroResponse['estimate'])){
				Estimate::create([
					'customer_id' => $data['customer_id'],
					'number' => $syncroResponse['estimate']['number'],
					'date' => now()->format('Y-m-d'),
					'note' => $data['note'],
					'quantity' => $data['quantity'],
					'syncro_product_id' => $data['syncro_product_id'],
					'estimate_subtotal' => $syncroResponse['estimate']['subtotal'],
					'estimate_total' => $syncroResponse['estimate']['total'],
					'estimate_tax' => $syncroResponse['estimate']['tax'],
					'syncro_estimate_id' => $syncroResponse['estimate']['id'],
					'status' => $syncroResponse['estimate']['status'],
					'invoice_total' => $invoiceTotal,
					'is_annual' => isset($annualEstimate) ? 0 : 1,
				]);

				return ['status' => true, 'message' => 'Estimate created successfully', 'statusCode' => 200];
			}

			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

}