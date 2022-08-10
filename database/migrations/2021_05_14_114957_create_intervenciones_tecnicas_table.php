<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntervencionesTecnicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('intervenciones_tecnicas', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_hora')->nullable();
            $table->foreignId('expediente_id')->nullable()->constrained();
            $table->boolean('plano')->default('false');
            $table->boolean('foto')->default('false');
            $table->boolean('video')->default('false');
            $table->string('descripcion', 2000)->nullable();
            $table->foreignId('perito_usuario_id')->nullable()->constrained('users');
            $table->foreignId('tecnico_revelador_usuario_id')->nullable()->constrained('users');
            $table->foreignId('planimetrista_usuario_id')->nullable()->constrained('users');
            $table->foreignId('fotografo_usuario_id')->nullable()->constrained('users');
            $table->foreignId('video_usuario_id')->nullable()->constrained('users');
            $table->foreignId('secretario_usuario_id')->nullable()->constrained('users');
            $table->foreignId('delegacion_dependencia_id')->nullable()->constrained('dependencias');
            $table->foreignId('interviniento_dependencia_id')->nullable()->constrained('dependencias');
            $table->foreignId('delito_id')->nullable()->constrained();
            $table->foreignId('lugar_id')->nullable()->constrained();
            $table->foreignId('estado_id')->constrained();
            $table->foreignId('tipo_colision_id')->nullable()->constrained('tipo_colision');
            $table->foreignId('tipo_intervencion_tecnica_id')->constrained('tipo_intervencion_tecnica');
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('intervenciones_tecnicas');
    }
}
