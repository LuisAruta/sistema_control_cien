<?php

namespace Database\Factories;

use App\Models\Dependencia;
use App\Models\IntervencionTecnica;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Delito;
use App\Models\Estado;
use App\Models\Expediente;
use App\Models\Lugar;
use App\Models\User;

class IntervencionTecnicaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = IntervencionTecnica::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'fecha' => $this->faker->dateTime(),
            'expediente_id' => Expediente::factory(),
            'descripcion' => $this->faker->randomElement(['Se inspecciona sector del patio de Panadería -Pan Tojarse- en la que sujetos ignorados habrían ingresado escalando una medianera, posteriormente dañando una puerta de un depósito para sustraer mercadería del interior y de un freezer. Se colectaron tres muestras con posible presencia de células epiteliales y ocho rastros papilares.',
                'AV. ROBO EN GRADO DE TENTATIVA. Se inspecciona oficina en la que funciona Estudio de Comercio Exterior - Despachante de Aduana en la que sujetos ignorados habrían forzado la puerta de ingreso. Del lugar no habrían sustraído elementos según lo manifestado por la víctima. Se colectó una muestra con posible presencia de células epiteliales.',
                'Se inspecciona living de vivienda particular en la que sujetos ignorados habrían ingresado escalando por el patio y posteriormente forzando una puerta de rejas, sustraen un televisor y una cortina. Se colectaron dos muestras con posible presencia de células epiteliales.',
                'Se inspecciona obra en construcción donde sujetos desconocidos previo escalar el limite de chapas del ingreso, forzando  tres puertas de los 5 departamentos, sustraen herramientas de construcción palas picos martillo neumáticos e intentan sustraer 6 rejas de las ventanas. colectándose tres hisopos con posible presencia de células epiteliales.',
                'El servicio solicitado a las 13:34 hs. queda SIN EFECTO por directivas de la Dra. Aguado. Quedando consignado en fojas 93 reverso del libro de novedades.',
                'Se inspecciona Vehículo Volskwagen Fox dominio LIU 266 color gris en el que se conducían tres sujetos que fueron aprehendidos, vinculados al Robo Agravado de calle Beltrán 942 de Godoy Cruz RC 15/20.  Se colectaron siete hisopos con posible células epiteliales y 24 rastros parciales papilares.',
                'El servicio solicitado a las 13 hs. por un vehículo recuperado queda SIN EFECTO para delegación Científica Central ya que el servicio sera realizado por la Delegación Científica Guaymallén debido a la demora en los servicios solicitados.',
                'Se inspecciona complejo departamento donde en horas mas temprana autores desconocidos previo a dañar la puerta del garaje, hall central y la puerta de acceso principal del departamento N°3 logran sustraer una play station, un jockstick, y un auricular del lugar se colecto 3 hisopos con ADN y 6 rastros.',
                'Se inspeccionó móvil policial interno 3267 de Policía Vial, marca Hino, modelo 300, tipo grúa, dominio AC 864 FW, al cual le habrían arrojado piedras el día 01 de Enero en el Barrio San Martín, provocando oquedades en puerta derecha y panel frontal como así también rotura de acrílico de óptica derecha.',
                'El servicio solicitado a las 13:26 hs queda SIN EFECTO ya que el operador del CEO comunica mediante frecuencia radial que interviene la oficina fiscal nº 6 correspondiente a jurisdicción de Las Heras, no tomando intervención la oficina fiscal 13. Novedad consignada en foja 95 del libro de novedades de la dependencia.-',
                'Se realiza inspección de elementos varios, licuadora ATMA, un reflector led, dos botellas de desengranaste CIF y una de detergente  GIGANTE.']),
            'plano' => $this->faker->boolean,
            'foto' => $this->faker->boolean,
            'video' => $this->faker->boolean,
            'delito_id' => Delito::all()->random(1)[0]->id,
            'delegacion_dependencia_id' => Dependencia::where('cientifica', true)->inRandomOrder(1)->get()[0]->id,
            'interviniento_dependencia_id' => Dependencia::where('cientifica', false)->inRandomOrder(1)->get()[0]->id,
            'lugar_id' => Lugar::factory(),
            'estado_id' => Estado::all()->random(1)[0]->id,
            'perito_usuario_id' => User::where('perito', true)->get()->random(1)[0]->id,
            'tecnico_revelador_usuario_id' => User::where('perito', true)->get()->random(1)[0]->id,
            'planimetrista_usuario_id' => User::where('perito', true)->get()->random(1)[0]->id,
            'fotografo_usuario_id' => User::where('perito', true)->get()->random(1)[0]->id,
            'video_usuario_id' => User::where('perito', true)->get()->random(1)[0]->id,
        ];
    }
}
