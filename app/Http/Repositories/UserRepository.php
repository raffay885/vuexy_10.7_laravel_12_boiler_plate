<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\UserRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Traits\Syncro;

class UserRepository implements UserRepositoryInterface{
	
	use Syncro;

	public function getDataTable(array $filters = []){
		$users = $this->find($filters);
		return DataTables::of($users)->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		$users = User::query();
		if(isset($filters['user_type'])){
			$users->where('user_type', $filters['user_type']);
		}

		return $users->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		$user = User::query();
		if(isset($filters['id'])){
			$user->where('id', $filters['id']);
		}

		return $user->firstOrFail();
	}

	public function create(array $data){
		try{
			$syncroCustomerId = null;
			$syncroResponse = $this->syncroGet('customers', ['email' => $data['email']]);
			if($syncroResponse){
				if(isset($syncroResponse['customers']) && !empty($syncroResponse['customers'])){
					$syncroCustomerId = $syncroResponse['customers'][0]['id'];
				}
			}

			$user = User::create([...$data, 'syncro_customer_id' => $syncroCustomerId]);
			if($data['user_type'] == 'customer'){
				$user->assignRole('Customer');
			}
		
			return ['status' => true, 'message' => 'User created successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function update(int $id, array $data){
		try{
			$user = $this->findOne(['id' => $id]);
			$syncroCustomerId = $user->syncro_customer_id;
			$syncroResponse = $this->syncroGet('customers', ['email' => $data['email']]);

			if($syncroResponse){
				if(isset($syncroResponse['customers']) && !empty($syncroResponse['customers'])){
					$syncroCustomerId = $syncroResponse['customers'][0]['id'];
				}
			}
			$user->update([...$data, 'syncro_customer_id' => $syncroCustomerId]);

			return ['status' => true, 'message' => 'User updated successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function delete(int $id){
		try{
			$user = $this->findOne(['id' => $id]);
			$user->delete();
			
			return ['status' => true, 'message' => 'User deleted successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}
}