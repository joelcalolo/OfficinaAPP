<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTecnicoAndObservacoesToServicosPrestadosTable extends Migration
{
    public function up()
    {
        Schema::table('servicos_prestados', function (Blueprint $table) {
            $table->string('tecnico')->nullable(); // Nome do técnico/mecânico
            $table->text('observacoes')->nullable(); // Observações sobre o serviço
        });
    }

    public function down()
    {
        Schema::table('servicos_prestados', function (Blueprint $table) {
            $table->dropColumn('tecnico');
            $table->dropColumn('observacoes');
        });
    }
}