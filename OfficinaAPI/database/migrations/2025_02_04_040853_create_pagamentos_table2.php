<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->decimal('valor', 10, 2);
            $table->dateTime('data');
            $table->string('codigo_referencia')->unique();
            $table->string('status')->default('pendente'); // pendente, pago, cancelado
            $table->foreignId('ordem_servico_id')->constrained('ordem_servicos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pagamentos');
    }
};