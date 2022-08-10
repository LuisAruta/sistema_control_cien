<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDelitoTipoIntervencionTecnicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('delito_tipo_intervencion_tecnica', function (Blueprint $table) {
            $table->integer('delito_id')->unsigned();
            $table->integer('tipo_intervencion_tecnica_id')->unsigned();

            $table->unique(['delito_id', 'tipo_intervencion_tecnica_id']);
            $table->foreign('delito_id')->references('id')->on('delitos')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('tipo_intervencion_tecnica_id')->references('id')->on('tipo_intervencion_tecnica')
                ->onDelete('cascade')->onUpdate('cascade');
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
        Schema::dropIfExists('delito_tipo_intervencion_tecnica');
    }
}
