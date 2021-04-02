<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_players', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->integer('age');
            $table->integer('height');
            $table->float('weight');
            $table->float('BMI');

            $table->bigInteger('country_id')->unsigned()->index();    
            $table->foreign('country_id')->references('id')->on('countries');

            $table->bigInteger('team_id')->unsigned()->index();    
            $table->foreign('team_id')->references('id')->on('teams');

            $table->integer('value');
            $table->integer('wage');

            $table->integer('goals');
            $table->integer('assists');
            $table->integer('matches');

            $table->integer('ntGoals');
            $table->integer('ntAssists');
            $table->integer('ntMatches');

            $table->integer('injuryDays');

            $table->boolean('national')->default(0);

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
        Schema::dropIfExists('team_players');
    }
}
