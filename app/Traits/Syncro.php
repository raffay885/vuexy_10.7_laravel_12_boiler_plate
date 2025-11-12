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

	public function getCustomersList(array $filters = []){
		try {
			$this->initializeSyncro();
			if (!$this->subDomain || !$this->domain || !$this->apiKey) {
				Log::error('Syncro API credentials not configured');
				return null;
			}
						
			$response = Http::withOptions([
				'curl' => [
					CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				],
			])->withHeaders([
				'Authorization' => $this->apiKey,
				'Accept' => 'application/json',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
			])->get($this->baseUrl . '/customers?' . http_build_query($filters ?? []));

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

	public function getCustomerDetails(string $syncroCustomerId){
		try{
			$this->initializeSyncro();
			if (!$this->subDomain || !$this->domain || !$this->apiKey) {
				Log::error('Syncro API credentials not configured');
				return null;
			}

			$response = Http::withOptions([
				'curl' => [
					CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				],
			])->withHeaders([
				'Authorization' => $this->apiKey,
				'Accept' => 'application/json',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
			])->get($this->baseUrl . '/customers/' . $syncroCustomerId);

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

	public function getAssetTypes(){
		try{
			$this->initializeSyncro();
			if (!$this->subDomain || !$this->domain || !$this->apiKey) {
				Log::error('Syncro API credentials not configured');
				return null;
			}

			$response = Http::withOptions([
				'curl' => [
					CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				],
			])->withHeaders([
				'Authorization' => $this->apiKey,
				'Accept' => 'application/json',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
			])->get($this->baseUrl . '/asset_types');
		
			if ($response->successful()) {
				return $response->json();
			}

			Log::error('Syncro API Error: ' . $response->status() . ' - ' . $response->body());
			return ['asset_types' => []];
		} catch (\Exception $e) {
			Log::error('Syncro API Exception: ' . $e->getMessage());
			return null;
		}
	}

	public function getCustomerAssetsList(array $filters = []){
		try{
			$this->initializeSyncro();
			if (!$this->subDomain || !$this->domain || !$this->apiKey) {
				Log::error('Syncro API credentials not configured');
				return null;
			}

			$response = Http::withOptions([
				'curl' => [
					CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				],
			])->withHeaders([
				'Authorization' => $this->apiKey,
				'Accept' => 'application/json',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
			])->get($this->baseUrl . '/customer_assets?' . http_build_query($filters ?? []));

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

	public function getCustomerAssetDetails(string $syncroAssetId){
		try{
			$this->initializeSyncro();
			if (!$this->subDomain || !$this->domain || !$this->apiKey) {
				Log::error('Syncro API credentials not configured');
				return null;
			}

			$response = Http::withOptions([
				'curl' => [
					CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				],
			])->withHeaders([
				'Authorization' => $this->apiKey,
				'Accept' => 'application/json',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
			])->get($this->baseUrl . '/customer_assets/' . $syncroAssetId);

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

	public function createCustomerAsset(array $data){
		try{
			$this->initializeSyncro();
			if (!$this->subDomain || !$this->domain || !$this->apiKey) {
				Log::error('Syncro API credentials not configured');
				return null;
			}

			$response = Http::withOptions([
				'curl' => [
					CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				],
			])->withHeaders([
				'Authorization' => $this->apiKey,
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
			])->post($this->baseUrl . '/customer_assets', $data);

			if ($response->successful()) {
				return $response->json();
			}

			Log::error('Syncro API Error: ' . $response->status() . ' - ' . $response->body());
			return null;
		}catch (\Exception $e) {
			Log::error('Syncro API Exception: ' . $e->getMessage());
			return null;
		}
	}

	public function updateCustomerAsset(string $syncroAssetId, array $data){
		try{
			$this->initializeSyncro();
			if (!$this->subDomain || !$this->domain || !$this->apiKey) {
				Log::error('Syncro API credentials not configured');
				return null;
			}

			$response = Http::withOptions([
				'curl' => [
					CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				],
			])->withHeaders([
				'Authorization' => $this->apiKey,
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
			])->put($this->baseUrl . '/customer_assets/' . $syncroAssetId, $data);

			if ($response->successful()) {
				return $response->json();
			}

			Log::error('Syncro API Error: ' . $response->status() . ' - ' . $response->body());
			return null;
		}catch (\Exception $e) {
			Log::error('Syncro API Exception: ' . $e->getMessage());
			return null;
		}
	}

	public function createEstimate(array $data){
		try{
			$this->initializeSyncro();
			if (!$this->subDomain || !$this->domain || !$this->apiKey) {
				Log::error('Syncro API credentials not configured');
				return null;
			}

			$response = Http::withOptions([
				'curl' => [
					CURLOPT_SSLVERSION => CURL_SSLVERSION_TLSv1_2,
				],
			])->withHeaders([
				'Authorization' => $this->apiKey,
				'Accept' => 'application/json',
				'Content-Type' => 'application/json',
				'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
			])->post($this->baseUrl . '/estimates', $data);

			if ($response->successful()) {
				return $response->json();
			}

			Log::error('Syncro API Error: ' . $response->status() . ' - ' . $response->body());
			return null;
		}catch (\Exception $e) {
			Log::error('Syncro API Exception: ' . $e->getMessage());
			return null;
		}
	}
}