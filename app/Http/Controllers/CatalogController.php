<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Client\RequestException;
use App\Models\CatalogInventory;

class CatalogController extends Controller
{
	private $titlePage = 'Beranda';

	public function index(Request $request): View
	{
		$titlePage = 'Katalog';
		try {
			/* My old code
			$input = $request->only(['criteria', 'search']);
			if (!empty($input['criteria']) && !empty($input['search'])) {
				$paginatedCatalog = CatalogInventory::query()
					->whereLike($input['criteria'], "%{$input['search']}%")
					->paginate(20);
			} else {
				$paginatedCatalog = CatalogInventory::paginate(20);
			}
			 */
			$query = CatalogInventory::query();

			// Existing search
			if ($request->filled('criteria') && $request->filled('search')) {
				$query->whereLike(
					$request->criteria,
					"%{$request->search}%"
				);
			}

			// Additional filters
			if ($request->filled('author')) {
				$query->whereLike('Author', "%{$request->author}%");
			}

			if ($request->filled('publisher')) {
				$query->whereLike('Publisher', "%{$request->publisher}%");
			}

			if ($request->filled('isbn')) {
				$query->whereLike('ISBN', "%{$request->isbn}%");
			}

			if ($request->filled('year')) {
				$query->where('publishYear', $request->year);
			}

			$paginatedCatalog = $query
				->paginate(20)
				->withQueryString();

			return view('catalogs', [
				'titlePage' => $titlePage,
				'catalogs' => $paginatedCatalog,
				'message' => null,
			]);
        } catch (RequestException $e) {
			return view('catalogs', [
				'titlePage' => $titlePage,
                'message' => 'Gagal mendapatkan data katalog.',
				'error' => $e->getMessage(),
			]);
        }
	}

	public function show(Request $request, string $id): View
	{
		$titlePage = 'Detail Katalog';
		try {
            $catalog = CatalogInventory::find($id);
			return view('catalog-details', [
				'titlePage' => $titlePage,
				'catalog_details' => $catalog,
				'message' => null,
			]);
        } catch (RequestException $e) {
			return view('catalog-details', [
				'titlePage' => $titlePage,
				'catalog_details' => [],
				'message' => 'Katalog tidak ditemukan.',
			]);
        }
	}
}
