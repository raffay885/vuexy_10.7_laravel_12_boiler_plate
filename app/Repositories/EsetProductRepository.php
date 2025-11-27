<?php

namespace App\Repositories;

use App\Interfaces\EsetProductRepositoryInterface;
use Yajra\DataTables\Facades\DataTables;
use App\Models\EsetProduct;

class EsetProductRepository implements EsetProductRepositoryInterface{

	public function getDataTable(array $filters = []){
		return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return EsetProduct::with('syncroProduct')->where([ ...$filters ])->orderBy('id', 'desc')->get();
	}

	public function findOne(array $filters = []){
		return EsetProduct::with('syncroProduct')->where([ ...$filters ])->orderBy('id', 'desc')->first();
	}

	public function create(array $data){
		try{
			$isSyncroProductApplied = $this->findOne(['syncro_product_id' => $data['syncro_product_id']]);
			if($isSyncroProductApplied){
				return ['status' => false, 'message' => 'Syncro Product already applied to an Eset product', 'statusCode' => 400];
			}

			EsetProduct::create([...$data]);
			return ['status' => true, 'message' => 'Eset product created successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function update(int $id, array $data){
		try{
			$esetProduct = $this->findOne(['id' => $id]);
			if(!$esetProduct){
				return ['status' => false, 'message' => 'Eset product not found', 'statusCode' => 404];
			}

			$isSyncroProductApplied = EsetProduct::where('syncro_product_id', $data['syncro_product_id'])->where('id', '!=', $id)->first();
			if($isSyncroProductApplied){
				return ['status' => false, 'message' => 'Syncro Product already applied to an Eset product', 'statusCode' => 400];
			}

			$esetProduct->update([...$data]);
			return ['status' => true, 'message' => 'Eset product updated successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

	public function delete(int $id){
		try{
			$esetProduct = $this->findOne(['id' => $id]);
			if(!$esetProduct){
				return ['status' => false, 'message' => 'Eset product not found', 'statusCode' => 404];
			}
			
			$esetProduct->delete();
			return ['status' => true, 'message' => 'Eset product deleted successfully', 'statusCode' => 200];
		} catch (\Exception $e) {
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

}