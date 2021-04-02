<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamPlayersHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_players_history', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('player_id')->unsigned()->index();    
            $table->foreign('player_id')->references('id')->on('team_players');

            $table->integer('skillForm');
            $table->integer('skillExperience');
            $table->integer('skillTeamwork');
            $table->integer('skillDiscipline');

            $table->integer('skillStamina');
            $table->integer('skillPace');
            $table->integer('skillTechnique');
            $table->integer('skillPassing');
            $table->integer('skillKeeper');
            $table->integer('skillDefending');
            $table->integer('skillPlaymaking');
            $table->integer('skillScoring');

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
        Schema::dropIfExists('team_players_history');
    }
}
