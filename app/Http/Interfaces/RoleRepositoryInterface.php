<?php

namespace App\Http\Interfaces;

interface RoleRepositoryInterface{

	public function getDataTable(array $filters = []);

	public function find(array $filters = []);

	public function findOne(array $filters = []);

	public function create(array $data);

	public function update(int $id, array $data);

	public function delete(int $id);

}