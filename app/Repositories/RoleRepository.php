<?php

namespace App\Repositories;

use App\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;
use Spatie\Permission\Models\Permission;


class RoleRepository implements RoleRepositoryInterface{

	public function getDataTable(array $filters = []){
		$roles = $this->find($filters);
        return DataTables::of($roles)->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return Role::where('id', '!=', 1)->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		$role = Role::query();
		if(isset($filters['id'])){
			$role->where('id', $filters['id']);
		}

		return $role->firstOrFail();
	}

	public function create(array $data){
		try{
			$role = Role::create($data);
			$role->syncPermissions($data['permissions']);

			return ['status' => true, 'message' => 'Role created successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function update(int $id, array $data){
		try{
			$role = $this->findOne(['id' => $id]);
			$role->update($data);
			$role->syncPermissions($data['permissions']);

			return ['status' => true, 'message' => 'Role updated successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function delete(int $id){
		try{
			$role = $this->findOne(['id' => $id]);
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