<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    if (!Schema::hasColumn('users', 'rol')) {
        Schema::table('users', function (Blueprint $table) {
            //$table->string('rol')->default('VISITANTE');
        });
    }
}


public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('rol');
    });
}

};
