<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CatalogInventory extends Model
{
	use HasFactory;

	protected $casts = [
		'synced_at' => 'datetime',
	];
}
