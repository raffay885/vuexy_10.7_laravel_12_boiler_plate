<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;

class RoleRepository implements RoleRepositoryInterface{

	public function getDataTable(array $filters = []){
        return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return Role::where([
			...$filters,
			'id' => '!=', 1
		])->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		return Role::where([ ...$filters ])->orderBy('id', 'desc')->first();
	}

	public function create(array $data){
		try{
			$role = Role::create([ ...$data ]);
			$role->syncPermissions($data['permissions']);

			return ['status' => true, 'message' => 'Role created successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function update(int $id, array $data){
		try{
			$role = $this->findOne(['id' => $id]);
			if(!$role){
				return ['status' => false, 'message' => 'Role not found', 'statusCode' => 404];
			}

			$role->update([ ...$data ]);
			$role->syncPermissions($data['permissions']);

			return ['status' => true, 'message' => 'Role updated successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function delete(int $id){
		try{
			$role = $this->findOne(['id' => $id]);
			if(!$role){
				return ['status' => false, 'message' => 'Role not found', 'statusCode' => 404];
			}

			$role->delete();
			return ['status' => true, 'message' => 'Role deleted successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function getPermissions(array $filters = []){
		return Permission::orderBy('id', 'desc')->get();
	}
}