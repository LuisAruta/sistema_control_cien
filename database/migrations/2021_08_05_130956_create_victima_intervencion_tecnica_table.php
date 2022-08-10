<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVictimaIntervencionTecnicaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('victima_intervencion_tecnica', function (Blueprint $table) {
            $table->integer('victima_id')->unsigned();
            $table->integer('intervencion_tecnica_id')->unsigned();

            $table->unique(['victima_id', 'intervencion_tecnica_id']);
            $table->foreign('victima_id')->references('id')->on('victimas')
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
        Schema::dropIfExists('victima_intervencion_tecnica');
    }
}
