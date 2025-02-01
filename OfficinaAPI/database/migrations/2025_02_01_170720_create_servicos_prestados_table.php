<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicosPrestadosTable extends Migration
{
    public function up()
    {
        Schema::create('servicos_prestados', function (Blueprint $table) {
            $table->id();
            $table->foreignId('viatura_id')->constrained()->onDelete('cascade');
            $table->dateTime('data');
            $table->decimal('valor_total', 8, 2)->default(0); // Valor total do serviço prestado
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('servicos_prestados');
    }
}