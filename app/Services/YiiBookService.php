<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;

class YiiBookService
{
    private string $baseUrl;
    private string $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.yii.base_url');
        $this->apiKey  = config('services.yii.api_key');
    }

    private function client()
    {
        return Http::withHeaders([
            'X-Api-Key' => $this->apiKey,
            'Accept'    => 'application/json',
        ])->baseUrl($this->baseUrl);
    }

    public function getAllBooks(array $filters = []): array
    {
        return $this->client()
			->get('/api/catalogs')
            ->throw()
            ->json();
    }

    public function getBook(int $id): array
    {
        return $this->client()
            ->get("/api/catalogs/{$id}")
            ->throw()
            ->json();
    }
}
