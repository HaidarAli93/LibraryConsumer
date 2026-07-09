<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Services\CatalogInventorySyncService;

class DashboardController extends Controller
{
	public function index(Request $request): View {
		return view('dashboard', []);
	}

	public function sync(Request $request, CatalogInventorySyncService $service): RedirectResponse {
		try {
			$service->sync();

			return redirect()
				->route('dashboard')
				->with('toast', [
					'type' => 'success',
					'message' => 'Catalog synchronized successfully.'
				]);
		} catch (\Throwable $e) {
			return redirect()
				->route('dashboard')
				->with('toast', [
					'type' => 'danger',
					'message' => 'Catalog synchronization failed.'
				]);
		}
	}
}
