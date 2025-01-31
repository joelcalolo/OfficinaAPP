<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDataToViaturaServicoTable extends Migration
{
    public function up()
    {
        Schema::table('viatura_servico', function (Blueprint $table) {
            $table->dateTime('data')->nullable(); // Adiciona o campo data
        });
    }

    public function down()
    {
        Schema::table('viatura_servico', function (Blueprint $table) {
            $table->dropColumn('data'); // Remove o campo data
        });
    }
}
