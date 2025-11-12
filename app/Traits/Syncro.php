<?php

namespace App\Traits;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

trait Syncro{

	protected $baseUrl;
	protected $apiKey;
	protected $subDomain;
	protected $domain;

	public function initializeSyncro(){
		$this->subDomain = env('SYNCRO_SUBDOMAIN');
		$this->domain = env('SYNCRO_DOMAIN');
		$this->apiKey = env('SYNCRO_API_KEY');
		$this->baseUrl = "https://{$this->subDomain}.{$this->domain}/api/v1";
	}

	public function syncroClient(){
        $this->initializeSyncro();
        if (!$this->subDomain || !$this->domain || !$this->apiKey) {
            Log::error('Syncro API credentials not configured');
            throw new \RuntimeException('Syncro API credentials missing');
        }

        return Http::withOptions([
            'curl' => [
                CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
            ],
        ])->withHeaders([
            'Authorization' => $this->apiKey,
            'Accept' => 'application/json',
            'User-Agent' => 'SyncroIntegration/1.0 (+https://yourapp.com)',
        ]);
    }

	public function syncroGet(string $endpoint, array $params = []){
		try{
			$response = $this->syncroClient()->get("{$this->baseUrl}/{$endpoint}", $params);
			if ($response->successful()) {
				return $response->json();
			}

			Log::error('Syncro API Error: ' . $response->status() . ' - ' . $response->body());
			return null;
		} catch (\Exception $e) {
  			Log::error('Syncro API Exception: ' . $e->getMessage());
			return null;
        }
    }

	public function syncroPost(string $endpoint, array $payload = []){
		try{
			$response = $this->syncroClient()->post("{$this->baseUrl}/{$endpoint}", $payload);
			if ($response->successful()) {
				return $response->json();
			}

			Log::error('Syncro API Error: ' . $response->status() . ' - ' . $response->body());
			return null;
		} catch (\Exception $e) {
  			Log::error('Syncro API Exception: ' . $e->getMessage());
			return null;
        }
    }

	public function syncroPut(string $endpoint, array $payload = []){
		try{
			$response = $this->syncroClient()->put("{$this->baseUrl}/{$endpoint}", $payload);
			if ($response->successful()) {
				return $response->json();
			}

			Log::error('Syncro API Error: ' . $response->status() . ' - ' . $response->body());
			return null;
		} catch (\Exception $e) {
  			Log::error('Syncro API Exception: ' . $e->getMessage());
			return null;
        }
    }
}