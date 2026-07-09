<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return view('home', [
		'titlePage' => 'Beranda',
	]);
})->name('home');

Route::controller(CatalogController::class)->group(function () {
	Route::get('/catalogs', 'index')->name('catalog');
	Route::get('/catalogs/{id}', 'show')->name('catalog.details');
});

Route::get('/admin/login', [AuthController::class, 'index'])->name('dashboard.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('dashboard.login');

Route::controller(DashboardController::class)->group(function () {
	Route::get('/admin', 'index')->name('dashboard');
	Route::post('/admin', 'sync')->name('dashboard.sync');
});
