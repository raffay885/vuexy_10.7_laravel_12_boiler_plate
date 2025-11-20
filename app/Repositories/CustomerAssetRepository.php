<?php

namespace App\Repositories;

use App\Interfaces\CustomerAssetRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
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
		return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return CustomerAsset::with('customer')->where([ ...$filters ])->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		return CustomerAsset::with('customer')->where([ ...$filters ])->orderBy('id', 'desc')->first();
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
			if(!$customerAsset){
				return ['status' => false, 'message' => 'Customer asset not found', 'statusCode' => 404];
			}

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
			if(!$customerAsset){
				return ['status' => false, 'message' => 'Customer asset not found', 'statusCode' => 404];
			}

			$customerAsset->delete();			
			return ['status' => true, 'message' => 'Customer asset deleted successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}
}