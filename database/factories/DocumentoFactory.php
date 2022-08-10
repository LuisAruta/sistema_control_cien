<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Documento;

class DocumentoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Documento::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'tipo_documento' => $this->faker->randomElement(['DNI', 'LC', 'LE', 'EXT', 'Pasaporte']),
            'numero' => $this->faker->numberBetween(20000000, 60000000),
        ];
    }
}
