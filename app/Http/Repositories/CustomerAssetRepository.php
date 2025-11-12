<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\CustomerAssetRepositoryInterface;
use App\Http\Interfaces\UserRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\CustomerAsset;
use App\Traits\Syncro;
class CustomerAssetRepository implements CustomerAssetRepositoryInterface{

	use Syncro;
	protected $userRepository;

	public function __construct(UserRepositoryInterface $userRepository){
		$this->userRepository = $userRepository;
	}

	public function getDataTable(array $filters = []){
		$customerAssets = $this->find($filters);
		return DataTables::of($customerAssets)->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		$customerAssets = CustomerAsset::with('customer');
		if(isset($filters['customer_id'])){
			$customerAssets->where('customer_id', $filters['customer_id']);
		}

		return $customerAssets->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		$customerAssets = CustomerAsset::query();
		if(isset($filters['id'])){
			$customerAssets->where('id', $filters['id']);
		}

		return $customerAssets->firstOrFail();
	}

	public function create(array $data){
		try{
			$customer = $this->userRepository->findOne(['id' => $data['customer_id']]);
			$syncroResponse = $this->syncroPost('customer_assets', [
				...$data,
				'properties' => (object)[],
				'customer_id' => $customer->syncro_customer_id,
			]);
			
			if($syncroResponse && isset($syncroResponse['asset'])){
				CustomerAsset::create([
					...$data,
					'syncro_asset_id' => $syncroResponse['asset']['id'],
				]);
				return ['status' => true, 'message' => 'Customer asset created successfully', 'statusCode' => 200];
			}

			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function update(int $id, array $data){
		try{
			$customerAsset = $this->findOne(['id' => $id]);
			$syncroResponse = $this->syncroPut('customer_assets/' . $customerAsset->syncro_asset_id, [
				...$data,
				'properties' => (object)[],
				'customer_id' => $customerAsset->customer->syncro_customer_id,
			]);

			if($syncroResponse && isset($syncroResponse['asset'])){
				$customerAsset->update([
					...$data,
					'syncro_asset_id' => $syncroResponse['asset']['id'],
				]);
				return ['status' => true, 'message' => 'Customer asset updated successfully', 'statusCode' => 200];
			}

			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function delete(int $id){
		try{
			$customerAsset = $this->findOne(['id' => $id]);
			$customerAsset->delete();
			
			return ['status' => true, 'message' => 'Customer asset deleted successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}
}