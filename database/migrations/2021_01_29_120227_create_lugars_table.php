<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLugarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lugars', function (Blueprint $table) {
            $table->id();
            $table->string('latitud', 100)->nullable();
            $table->string('longitud', 100)->nullable();
            $table->string('departamento', 100)->nullable();
            $table->string('barrio', 100)->nullable();
            $table->string('localidad', 100)->nullable();
            $table->string('calle', 100)->nullable();
            $table->string('numero', 10)->nullable();
            $table->string('numero_departamento', 10)->nullable();
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
        Schema::dropIfExists('lugars');
    }
}
