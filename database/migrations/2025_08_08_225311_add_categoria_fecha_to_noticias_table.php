<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoriaFechaToNoticiasTable extends Migration
{
    public function up()
    {
        Schema::table('noticias', function (Blueprint $table) {
            // Las dejamos NULL para evitar errores con datos antiguos
            $table->string('categoria')->nullable()->after('tipo');
            $table->date('fecha_documento')->nullable()->after('categoria');
        });
    }

    public function down()
    {
        Schema::table('noticias', function (Blueprint $table) {
            $table->dropColumn(['categoria', 'fecha_documento']);
        });
    }
}
