<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Traits\Syncro;

class UserRepository implements UserRepositoryInterface{
	
	use Syncro;

	public function getDataTable(array $filters = []){
		return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		$users = User::where([ ...$filters ]);
		if(isset($filters['user_type']) && $filters['user_type'] == 'admin'){
			$users = $users->whereNotIn('id', [1]);
		}
		$users = $users->orderBy('id', 'desc')->get();
		
		return $users;
	}

	public function findOne(array $filters = []){
		return User::where([ ...$filters ])->orderBy('id', 'desc')->first();
	}

	public function create(array $data){
		try{
			$syncroCustomerId = null;
			$syncroResponse = $this->syncroGet('customers', ['email' => $data['email']]);

			if(!$syncroResponse || !isset($syncroResponse['customers']) || empty($syncroResponse['customers'])){
				return ['status' => false, 'message' => 'Uh-oh! Email not found in Syncro', 'statusCode' => 404];
			}

			$syncroCustomerId = $syncroResponse['customers'][0]['id'];
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
			if(!$user){
				return ['status' => false, 'message' => 'User not found', 'statusCode' => 404];
			}

			$syncroCustomerId = null;
			$syncroResponse = $this->syncroGet('customers', ['email' => $data['email']]);

			if(!$syncroResponse || !isset($syncroResponse['customers']) || empty($syncroResponse['customers'])){
				return ['status' => false, 'message' => 'Uh-oh! Email not found in Syncro', 'statusCode' => 404];
			}

			$syncroCustomerId = $syncroResponse['customers'][0]['id'];
			$user->update([...$data, 'syncro_customer_id' => $syncroCustomerId]);

			return ['status' => true, 'message' => 'User updated successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function delete(int $id){
		try{
			$user = $this->findOne(['id' => $id]);
			if(!$user){
				return ['status' => false, 'message' => 'User not found', 'statusCode' => 404];
			}
			
			$user->delete();
			return ['status' => true, 'message' => 'User deleted successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}
}