<?php

namespace App\Repositories;

use App\Interfaces\SyncroProductRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SyncroProduct;

class SyncroProductRepository implements SyncroProductRepositoryInterface{

	public function getDataTable(array $filters = []){
		return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return SyncroProduct::where([ ...$filters ])->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		return SyncroProduct::where([ ...$filters ])->orderBy('id', 'desc')->first();
	}

	public function create(array $data){
		try{
			SyncroProduct::create([...$data]);
			return ['status' => true, 'message' => 'Syncro product created successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function update(int $id, array $data){
		try{
			$syncroProduct = $this->findOne(['id' => $id]);
			if(!$syncroProduct){
				return ['status' => false, 'message' => 'Syncro product not found', 'statusCode' => 404];
			}

			$syncroProduct->update([...$data]);
			return ['status' => true, 'message' => 'Syncro product updated successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function delete(int $id){
		try{
			$syncroProduct = $this->findOne(['id' => $id]);
			if(!$syncroProduct){
				return ['status' => false, 'message' => 'Syncro product not found', 'statusCode' => 404];
			}
			
			$syncroProduct->delete();
			return ['status' => true, 'message' => 'Syncro product deleted successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}
	
}