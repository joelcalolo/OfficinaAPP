<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateViaturaServicoTable extends Migration
{
    public function up()
    {
        Schema::table('viatura_servico', function (Blueprint $table) {
            $table->foreignId('servico_prestado_id')->constrained('servicos_prestados')->onDelete('cascade');
            $table->integer('quantidade')->default(1); // Quantidade do serviço
            $table->decimal('valor_unitario', 8, 2)->nullable(); // Valor unitário do serviço
        });
    }

    public function down()
    {
        Schema::table('viatura_servico', function (Blueprint $table) {
            $table->dropForeign(['servico_prestado_id']);
            $table->dropColumn(['servico_prestado_id', 'quantidade', 'valor_unitario']);
        });
    }
}