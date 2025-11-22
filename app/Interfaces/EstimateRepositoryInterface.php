<?php

namespace App\Interfaces;

interface EstimateRepositoryInterface{

	public function getDataTable(array $filters = []);

	public function find(array $filters = []);

	public function count(array $filters = []);

	public function findOne(array $filters = []);

	public function create(array $data);

}