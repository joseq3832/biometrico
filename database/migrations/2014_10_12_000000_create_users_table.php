<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('codpersona');
            $table->string('cedpersona')->unique();
            $table->string('apepersona');
            $table->string('nompersona');
            $table->string('corpersona')->unique();
            $table->string('email');
            $table->string('telconvencionalpersona');
            $table->string('telcelularpersona');
            $table->string('sexo');
            $table->string('tippersona');
            $table->string('huella');
            $table->string('clave');
            $table->string('doc');
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
