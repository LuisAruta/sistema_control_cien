<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Lugar;

class LugarFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lugar::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'latitud' => $this->faker->latitude,
            'longitud' => $this->faker->longitude,
            'departamento' => $this->faker->state,
            'barrio' => $this->faker->streetName,
            'localidad' => $this->faker->state,
            'calle' => $this->faker->streetName,
            'numero' => $this->faker->numberBetween(1,2000),
            'numero_departamento' => $this->faker->numberBetween(1,50),
        ];
    }
}
