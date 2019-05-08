<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_roles', function (Blueprint $table) {
            $table->increments('id');
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            $table->timestamps();
        });

        Schema::table('user_roles', function (Blueprint $table) {
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
        });
        Schema::table('user_roles', function (Blueprint $table) {
            $table->foreign('role_id')
                ->references('id')
                ->on('roles');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_roles');
    }
}
