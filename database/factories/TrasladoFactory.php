<?php

namespace Database\Factories;

use App\Models\Dependencia;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Documento;
use App\Models\Expediente;
use App\Models\Lugar;
use App\Models\Traslado;
use App\Models\User;

class TrasladoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Traslado::class;

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
            'acompanante_usuario_id' => User::where('perito', true)->get()->random(1)[0]->id,
            'cadaver_nombre' => $this->faker->firstName. ' '.$this->faker->lastName,
            'cadaver_documento_id' => Documento::factory(),
            'legajo_cuerpo_medico_forense' => $this->faker->numberBetween(1, 300).'/'.$this->faker->randomElement(['19', '20', '21']),
            'lugar_id' => Lugar::factory(),
            'foto' => $this->faker->boolean,
            'delegacion_dependencia_id' => Dependencia::where('cientifica', true)->inRandomOrder(1)->get()[0]->id,
            'interviniento_dependencia_id' => Dependencia::where('cientifica', false)->inRandomOrder(1)->get()[0]->id,
        ];
    }
}
