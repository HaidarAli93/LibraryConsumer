<?php

namespace Database\Factories;

use App\Models\CatalogInventory;
use Illuminate\Database\Eloquent\Factories\Factory;

/*
|--------------------------------------------------------------------------
| Save as database/factories/CatalogInventoryFactory.php
| Matches the real catalog_inventories migration exactly.
|--------------------------------------------------------------------------
*/
class CatalogInventoryFactory extends Factory
{
    protected $model = CatalogInventory::class;

    public function definition(): array
    {
        return [
            'external_id' => $this->faker->unique()->numberBetween(1, 999999),
            'BIBID' => 'BIB'.$this->faker->unique()->numerify('######'),
            'Title' => $this->faker->sentence(4),
            'Author' => $this->faker->name(),
            'Edition' => '1st',
            'Publisher' => $this->faker->company(),
            'PublishLocation' => $this->faker->city(),
            'PublishYear' => (string) $this->faker->numberBetween(1970, 2025),
            'Subject' => $this->faker->word(),
            'PhysicalDescription' => 'xii, 300 p.; 23 cm',
            'ISBN' => $this->faker->isbn13(),
            'CallNumber' => $this->faker->bothify('###.# ???'),
            'Languages' => 'ID',
            'DeweyNo' => $this->faker->numerify('###.#'),
            'IsOPAC' => true,
            'CoverURL' => null,
            'Quantity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
