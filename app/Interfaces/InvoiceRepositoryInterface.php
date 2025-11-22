<?php

namespace App\Interfaces;

interface InvoiceRepositoryInterface{

	public function getDataTable(array $filters = []);

	public function find(array $filters = []);

	public function count(array $filters = []);

	public function create(array $data);

}