<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\User;
use App\Traits\Syncro;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserRepository implements UserRepositoryInterface{
	
	use Syncro;

	public function getDataTable(array $filters = []){
		return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->addColumn('role', function($row){
			return $row->getRoleNames()->first() ?? '';
		})->make(true);
	}

	public function find(array $filters = [], $limit = null){
		return User::where([ ...$filters ])->where('id', '!=', 1)->orderBy('id', 'desc')->limit($limit)->get();
	}

	public function findOne(array $filters = []){
		return User::where([ ...$filters ])->orderBy('id', 'desc')->first();
	}

	public function count(array $filters = []){
		return User::where([ ...$filters ])->count();
	}

	public function create(array $data){
		try{
			if($data['user_type'] == 'customer'){
				$syncroCustomerId = null;
				$syncroResponse = $this->syncroGet('customers', ['email' => $data['email']]);
				Log::info('Get Customer Syncro Response: ' . json_encode($syncroResponse));

				if(!$syncroResponse || !isset($syncroResponse['customers']) || empty($syncroResponse['customers'])){
					return ['status' => false, 'message' => 'Uh-oh! Email not found in Syncro', 'statusCode' => 404];
				}

				$syncroCustomerId = $syncroResponse['customers'][0]['id'];
				$user = User::create([
					...$data,
					'syncro_customer_id' => $syncroCustomerId,
					'password' => Hash::make(generateRandomPassword()),
				]);

				$user->assignRole('Customer');
			}else{
				$user = User::create([
					...$data,
					'password' => Hash::make($data['password']),
				]);

				$user->syncRoles($data['role']);
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

			if($user->user_type == 'customer'){
				$syncroCustomerId = null;
				$syncroResponse = $this->syncroGet('customers', ['email' => $data['email']]);
				Log::info('Update Customer Syncro Response: ' . json_encode($syncroResponse));

				if(!$syncroResponse || !isset($syncroResponse['customers']) || empty($syncroResponse['customers'])){
					return ['status' => false, 'message' => 'Uh-oh! Email not found in Syncro', 'statusCode' => 404];
				}

				$syncroCustomerId = $syncroResponse['customers'][0]['id'];
				$user->update([...$data, 'syncro_customer_id' => $syncroCustomerId]);
			}else{
				$user->update([...$data]);
			}

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