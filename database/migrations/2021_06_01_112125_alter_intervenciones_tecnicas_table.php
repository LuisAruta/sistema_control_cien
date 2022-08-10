<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterIntervencionesTecnicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('intervenciones_tecnicas', function (Blueprint $table) {
            $table->integer('numero_de_registro')->default(0);
            $table->integer('cantidad_oficios_pendientes')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('intervenciones_tecnicas', function (Blueprint $table) {
            $table->dropColumn('numero_de_registro');
            $table->dropColumn('cantidad_oficios_pendientes');
        });
    }
}
