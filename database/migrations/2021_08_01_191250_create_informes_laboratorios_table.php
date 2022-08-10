<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInformesLaboratoriosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('informes_laboratorios', function (Blueprint $table) {
            $table->id();
            $table->string('numero_informe', 30);
            $table->foreignId('tipo_informes_laboratorio_id')->constrained('tipo_informes_laboratorio');
            $table->foreignId('intervencion_tecnica_id')->constrained('intervenciones_tecnicas');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('informes_laboratorios');
    }
}
