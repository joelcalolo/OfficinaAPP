<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveViaturaIdFromViaturaServicoTable extends Migration
{
    public function up()
    {
        Schema::table('viatura_servico', function (Blueprint $table) {
            $table->dropForeign(['viatura_id']); // Remove a chave estrangeira
            $table->dropColumn('viatura_id'); // Remove a coluna viatura_id
        });
    }

    public function down()
    {
        Schema::table('viatura_servico', function (Blueprint $table) {
            $table->foreignId('viatura_id')->constrained()->onDelete('cascade'); // Reverte a alteração
        });
    }
}