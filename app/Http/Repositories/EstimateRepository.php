<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\EstimateRepositoryInterface;
use App\Models\Estimate;
use App\Traits\Syncro;
use App\Http\Interfaces\UserRepositoryInterface;

class EstimateRepository implements EstimateRepositoryInterface{

	use Syncro;
	protected $userRepository;

	public function __construct(UserRepositoryInterface $userRepository){
		$this->userRepository = $userRepository;
	}

	public function create(array $data){
		try{
			$customer = $this->userRepository->findOne(['id' => $data['customer_id']]);
			$lineItems = [];

			foreach($data['asset_ids'] as $assetId){
				$lineItems[] = [
					'item' => 'Asset ID: ' . $assetId,
					'name' => 'Asset ID: ' . $assetId,
					'quantity' => 1,
				];
			}

			$syncroResponse = $this->createEstimate([
				'number' => $data['number'],
				'date' => $data['date'],
				'customer_id' => $customer->syncro_customer_id,
				'status' => 'Fresh',
				'line_items' => $lineItems,
				'note' => $data['note'],
			]);

			if($syncroResponse && isset($syncroResponse['estimate'])){
				Estimate::create([
					'customer_id' => $data['customer_id'],
					'number' => $data['number'],
					'date' => $data['date'],
					'note' => $data['note'],
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