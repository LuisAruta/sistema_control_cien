<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOficiosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oficios', function (Blueprint $table) {
            $table->foreignId('estado_oficio_id')->constrained('estados_oficio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oficios', function (Blueprint $table) {
            $table->dropColumn('estado_oficio_id');
        });
    }
}
