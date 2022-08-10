<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinEfectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('sin_efectos', function (Blueprint $table) {
            $table->id();
            $table->integer('numero_de_registro')->default(0);
            $table->timestamp('fecha_hora_registro')->nullable();
            $table->timestamp('fecha_hora_solicitud')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('users');
            $table->foreignId('delegacion_dependencia_id')->nullable()->constrained('dependencias');
            $table->foreignId('lugar_id')->nullable()->constrained();
            $table->string('funcionario', 300)->nullable();
            $table->string('descripcion', 2000)->nullable();
            $table->integer('cantidad_oficios_pendientes')->default(0);
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
        Schema::dropIfExists('sin_efectos');
    }
}
