<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teams', function (Blueprint $table) {
            $table->id();
            $table->string('team_name');
            $table->bigInteger('country_id')->unsigned()->index();    
            $table->foreign('country_id')->references('id')->on('countries');

            $table->bigInteger('region_id')->unsigned()->index()->nullable();    
            $table->foreign('region_id')->references('id')->on('regions');

            $table->date('foundation_date');
            $table->float('rank');
            $table->string('stadium_name');
            $table->float('money', 12, 2);

            $table->integer('fanclubCount');
            $table->integer('fanclubMood');
            $table->integer('juniorsMax');

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
        Schema::dropIfExists('teams');
    }
}
