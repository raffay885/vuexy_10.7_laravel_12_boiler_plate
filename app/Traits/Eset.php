<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

trait Eset {

    protected $baseUrl;
    protected $userName;
    protected $password;
    protected $companyId;
    protected $accessToken;

    public function initializeEset() {
        $this->baseUrl = env('ESET_BASE_URL', 'https://mspapi.eset.com/api');
        $this->userName = env('ESET_USERNAME');
        $this->password = env('ESET_PASSWORD');
    }

    public function authenticate() {
		try{
			$this->initializeEset();
			if (!$this->userName || !$this->password) {
				Log::error('ESET API credentials missing');
				throw new \RuntimeException('ESET API credentials missing');
			}

			$payload = ['Username' => $this->userName, 'Password' => $this->password];
			$response = Http::withHeaders([
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
			])->post("{$this->baseUrl}/Token/Get", $payload);

			if ($response->successful()) {
				$this->accessToken = $response->json('accessToken');
				return $this->accessToken;
			}

			Log::error('ESET API Error: ' . $response->status() . ' - ' . $response->body());
			return null;
		}catch(\Exception $e){
			Log::error('Eset API Exception: ' . $e->getMessage());
			return null;
		}
    }

    public function esetClient() {
        if (!$this->accessToken) {
            $this->authenticate();
        }

        return Http::withHeaders([
            'Authorization' => "Bearer {$this->accessToken}",
            'Accept' => 'application/json'
        ]);
    }

    public function esetPost(string $endpoint, array $payload = []) {
        try {
            $response = $this->esetClient()->post("{$this->baseUrl}/{$endpoint}", $payload);
            if ($response->successful()) {
				return $response->json();
            }

            Log::error('ESET API Error: ' . $response->status() . ' - ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('ESET API Exception: ' . $e->getMessage());
            return null;
        }
    }
}
