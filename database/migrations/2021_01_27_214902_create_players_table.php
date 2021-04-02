<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();

            $table->integer('sk_player_id');
            $table->string('player_name');
            $table->integer('player_age');

            $table->integer('stamina');
            $table->integer('pace');
            $table->integer('technique');
            $table->integer('passing');
            $table->integer('keeper');
            $table->integer('defender');
            $table->integer('playmaker');
            $table->integer('striker');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('players');
    }
}
