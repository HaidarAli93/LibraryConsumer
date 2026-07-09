<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CatalogInventory;
use App\Services\YiiBookService;

class CtlgInvSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
	public function __construct(private YiiBookService $bookService) {
		//parent::__construct();
	}

    public function run(): void
    {
		$books = collect($this->bookService->getAllBooks());
		foreach ($books as $book) {
			$catalog = CatalogInventory::create(
				$book
			);
		}
    }
}
