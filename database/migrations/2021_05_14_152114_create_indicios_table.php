<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndiciosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('indicios', function (Blueprint $table) {
            $table->id();
            $table->string('detalle', 80);
            $table->bigInteger('cantidad');
            $table->foreignId('intervencion_tecnica_id')->constrained('intervenciones_tecnicas');
            $table->foreignId('tipo_indicio_id')->constrained('tipo_indicio');
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
        Schema::dropIfExists('indicios');
    }
}
