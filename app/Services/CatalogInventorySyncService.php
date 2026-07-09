<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use App\Services\YiiBookService;
use App\Models\CatalogInventory;

class CatalogInventorySyncService
{
	private const UPSERT_CHUNK_SIZE = 1000;

    public function __construct(
        private YiiBookService $api
    ) {}

    public function sync(): void
    {
        $books = collect($this->api->getAllBooks());

		$externalIds = [];
		$now = now();

        $rows = $books->map(function ($book) use ($now) {
            $book['external_id'] = $book['ID'];
            unset($book['ID']);

            $book['created_at'] = $now;
            $book['updated_at'] = $now;
            $book['synced_at'] = $now;

            return $book;
		});

		DB::transaction(function () use ($rows, $now) {
			$rows->chunk(self::UPSERT_CHUNK_SIZE)
			->each(function ($chunk) {
				CatalogInventory::upsert(
					$chunk->toArray(),
					['external_id']
				);
			});

			CatalogInventory::where('synced_at', '<', $now)->delete();
		});
    }
}
