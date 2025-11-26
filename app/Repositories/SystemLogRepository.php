<?php

namespace App\Repositories;

use App\Interfaces\SystemLogRepositoryInterface;
use App\Models\SystemLog;

class SystemLogRepository implements SystemLogRepositoryInterface{
	
	public function create(array $data){
		try{
			SystemLog::create($data);
			return ['status' => true, 'message' => 'System log created successfully', 'statusCode' => 200];
		}catch(\Exception $e){
			return ['status' => false, 'message' => 'Failed to create system log', 'statusCode' => 500];
		}
	}
}