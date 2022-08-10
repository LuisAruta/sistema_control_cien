<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Dependencia;
use App\Models\Documento;
use App\Models\Estado;
use App\Models\Expediente;
use App\Models\Lugar;
use App\Models\Necro;
use App\Models\User;

class NecroFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Necro::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fecha_hora' => $this->faker->dateTime(),
            'expediente_id' => Expediente::factory(),
            'perito_usuario_id' => User::where('perito', true)->get()->random(1)[0]->id,
            'delegacion_dependencia_id' => Dependencia::where('cientifica', true)->inRandomOrder(1)->get()[0]->id,
            'interviniento_dependencia_id' => Dependencia::where('cientifica', false)->inRandomOrder(1)->get()[0]->id,
            'cadaver_nombre' => $this->faker->firstName. ' '.$this->faker->lastName,
            'cadaver_documento_id' => Documento::factory(),
            'legajo_cuerpo_medico_forense' => $this->faker->numberBetween(1, 300).'/'.$this->faker->randomElement(['19', '20', '21']),
            'lugar_id' => Lugar::factory(),
            'foto' => $this->faker->boolean,
            'estado_id' => Estado::all()->random(1)[0]->id,
        ];
    }
}
