<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCardPropositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('card_propositions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->integer('cost');
            $table->integer('attack');
            $table->integer('health');
            $table->string('rarity');
            $table->integer('scraps_cost');
            $table->integer('scraps_earned');
            $table->string('user_name');
            $table->timestamps();
        });

        Schema::table('card_propositions', function (Blueprint $table) {
            $table->foreign('user_name')
                ->references('name')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('card_propositions');
    }
}
