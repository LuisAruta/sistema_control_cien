<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNecrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();

        Schema::create('necros', function (Blueprint $table) {
            $table->id();
            $table->timestamp('fecha_hora')->nullable();
            $table->foreignId('expediente_id')->nullable()->constrained();
            $table->foreignId('perito_usuario_id')->nullable()->constrained('users');
            $table->string('cadaver_nombre', 100)->nullable();
            $table->foreignId('cadaver_documento_id')->nullable()->constrained('documentos');
            $table->string('legajo_cuerpo_medico_forense', 50)->nullable();
            $table->foreignId('lugar_id')->nullable()->constrained();
            $table->boolean('foto')->default('false');
            $table->boolean('identificado')->default('true')->nullable();
            $table->foreignId('delegacion_dependencia_id')->nullable()->constrained('dependencias');
            $table->foreignId('interviniento_dependencia_id')->nullable()->constrained('dependencias');
            $table->foreignId('estado_id')->nullable()->constrained();
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
        Schema::dropIfExists('necros');
    }
}
