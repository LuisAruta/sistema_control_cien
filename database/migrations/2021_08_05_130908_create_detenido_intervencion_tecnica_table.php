<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetenidoIntervencionTecnicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detenido_intervencion_tecnica', function (Blueprint $table) {
            $table->integer('detenido_id')->unsigned();
            $table->integer('intervencion_tecnica_id')->unsigned();

            $table->unique(['detenido_id', 'intervencion_tecnica_id']);
            $table->foreign('detenido_id')->references('id')->on('detenidos')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('intervencion_tecnica_id')->references('id')->on('intervenciones_tecnicas')
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
        Schema::dropIfExists('detenido_intervencion_tecnica');
    }
}
