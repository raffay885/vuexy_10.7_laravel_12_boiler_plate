<?php

namespace App\Interfaces;

interface UserRepositoryInterface{

	public function getDataTable(array $filters = []);

	public function find(array $filters = [], $limit = null);

	public function findOne(array $filters = []);

	public function count(array $filters = []);

	public function create(array $data);

	public function update(int $id, array $data);

	public function delete(int $id);
	
}