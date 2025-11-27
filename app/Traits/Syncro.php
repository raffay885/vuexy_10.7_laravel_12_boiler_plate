<?php

namespace App\Traits;

use App\Models\SystemLog;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

trait Syncro{

	protected $baseUrl;
	protected $apiKey;
	protected $subDomain;
	protected $domain;

	public function initializeSyncro(){
		$setting = Setting::first();
		$this->subDomain = $setting->syncro_subdomain;
		$this->domain = $setting->syncro_domain;
		$this->apiKey = $setting->syncro_api_key;
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
			SystemLog::create([
				'user_id' => Auth::check() ? Auth::id() : null,
				'source' => 'syncro',
				'end_point' => $endpoint,
				'method' => 'GET',
				'http_code' => $response->status(),
				'payload' => json_encode($params),
				'error_message' => !$response->successful() ? $response->body() : null,
				'status' => $response->successful() ? 'success' : 'error',
			]);

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
			SystemLog::create([
				'user_id' => Auth::check() ? Auth::id() : null,
				'source' => 'syncro',
				'end_point' => $endpoint,
				'method' => 'POST',
				'http_code' => $response->status(),
				'payload' => json_encode($payload),
				'error_message' => !$response->successful() ? $response->body() : null,
				'status' => $response->successful() ? 'success' : 'error',
			]);

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
			SystemLog::create([
				'user_id' => Auth::check() ? Auth::id() : null,
				'source' => 'syncro',
				'end_point' => $endpoint,
				'method' => 'PUT',
				'http_code' => $response->status(),
				'payload' => json_encode($payload),
				'error_message' => !$response->successful() ? $response->body() : null,
				'status' => $response->successful() ? 'success' : 'error',
			]);

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