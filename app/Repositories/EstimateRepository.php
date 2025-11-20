<?php

namespace App\Repositories;

use App\Interfaces\EstimateRepositoryInterface;
use App\Models\Estimate;
use App\Traits\Syncro;
use App\Interfaces\UserRepositoryInterface;

class EstimateRepository implements EstimateRepositoryInterface{

	use Syncro;
	protected $userRepository;

	public function __construct(UserRepositoryInterface $userRepository){
		$this->userRepository = $userRepository;
	}

	public function find(array $filters = []){
		$estimates = Estimate::query();
		if(isset($filters['customer_id'])){
			$estimates->where('customer_id', $filters['customer_id']);
		}

		return $estimates->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		$estimate = Estimate::query();
		if(isset($filters['syncro_estimate_id'])){
			$estimate->where('syncro_estimate_id', $filters['syncro_estimate_id']);
		}

		return $estimate->firstOrFail();
	}

	public function create(array $data){
		try{
			$customer = $this->userRepository->findOne(['id' => $data['customer_id']]);
			$syncroResponse = $this->syncroPost('estimates', [
				'date' => $data['date'],
				'customer_id' => $customer->syncro_customer_id,
				'status' => 'Approved',
				'line_items' => [
					[
						'product_id' => $data['syncro_product_id'],
						'quantity' => $data['quantity'],
					],
				],
				'note' => $data['note'],
			]);

			if($syncroResponse && isset($syncroResponse['estimate'])){
				Estimate::create([
					'customer_id' => $data['customer_id'],
					'number' => $syncroResponse['estimate']['number'],
					'date' => $data['date'],
					'note' => $data['note'],
					'quantity' => $data['quantity'],
					'syncro_product_id' => $data['syncro_product_id'],
					'estimate_subtotal' => $syncroResponse['estimate']['subtotal'],
					'estimate_total' => $syncroResponse['estimate']['total'],
					'estimate_tax' => $syncroResponse['estimate']['tax'],
					'syncro_estimate_id' => $syncroResponse['estimate']['id'],
					'status' => $syncroResponse['estimate']['status'],
					'estimate_invoice_id' => $syncroResponse['estimate']['invoice_id'],
				]);

				return ['status' => true, 'message' => 'Estimate created successfully', 'statusCode' => 200];
			}

			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

}