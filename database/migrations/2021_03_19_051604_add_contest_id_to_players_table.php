<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContestIdToPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->bigInteger('contest_id')->unsigned()->index()->nullable()->after('id');
            $table->foreign('contest_id')->references('id')->on('contest_edition');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('players', function (Blueprint $table) {
            $table->dropForeign(['contest_id']);
        });
        Schema::table('players', function (Blueprint $table) {
            $table->dropColumn('contest_id');
        });
    }
}
