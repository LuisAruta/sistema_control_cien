<?php

namespace Database\Factories;

use App\Models\Dependencia;
use App\Models\Expediente;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpedienteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Expediente::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $numeroRandom=$this->faker->numberBetween(1, 999999);
        $length=strlen($numeroRandom);
        $nroExpte=str_repeat('0', 10-$length).$numeroRandom;

        return [
            'numero_expediente' => $this->faker->numberBetween(200, 99999) . '/' . $this->faker->randomElement(['19', '20', '21']),
            'caratula' => Dependencia::where('cientifica', false)->inRandomOrder(1)->get()[0]->nombre,
        ];
    }
}
