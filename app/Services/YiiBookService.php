<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\RequestException;
use Generator;

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

    public function getAllBooks(array $filters = []): Generator
    {
        $page = 1;

        do {
            $response = $this->client()
                ->get('/api/catalogs', array_merge($filters, [
                    'page' => $page,
                    //'per-page' => $this->perPage,
                ]))
                ->throw();

            yield $response->json();

            $pageCount = (int) $response->header('X-Pagination-Page-Count');
            $page++;
        } while ($page <= $pageCount);
    }

    public function getBook(int $id): array
    {
        return $this->client()
            ->get("/api/catalogs/{$id}")
            ->throw()
            ->json();
    }
}
