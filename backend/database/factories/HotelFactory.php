<?php

namespace Database\Factories;

use App\Models\Hotel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Hotel>
 */
class HotelFactory extends Factory
{
    protected $model = Hotel::class;

    public function definition(): array
    {
        return [
            'name'      => strtoupper($this->faker->unique()->company()),
            'address'   => $this->faker->streetAddress(),
            'city_id'   => 1,
            'nit'       => $this->faker->unique()->numerify('########-#'),
            'max_rooms' => $this->faker->numberBetween(20, 100),
        ];
    }
}
