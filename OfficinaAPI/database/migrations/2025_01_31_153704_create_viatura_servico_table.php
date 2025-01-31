<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateViaturaServicoTable extends Migration
{
    public function up()
    {
        Schema::create('viatura_servico', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viatura_id')->constrained('viaturas')->onDelete('cascade');
            $table->foreignId('servico_id')->constrained('servicos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('viatura_servico');
    }
}