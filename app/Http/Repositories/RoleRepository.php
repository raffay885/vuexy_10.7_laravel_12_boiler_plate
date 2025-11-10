<?php

namespace App\Http\Repositories;

use App\Http\Interfaces\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;


class RoleRepository implements RoleRepositoryInterface{

	public function getDataTable(array $filters = []){
		$roles = $this->find($filters);
        return DataTables::of($roles)->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return Role::where($filters)->get();
	}

	public function findOne(array $filters = []){
		return Role::where($filters)->first();
	}

	public function create(array $data){
		return Role::create($data);
	}

	public function update(int $id, array $data){
		return Role::where('id', $id)->update($data);
	}

	public function delete(int $id){
		return Role::where('id', $id)->delete();
	}

}