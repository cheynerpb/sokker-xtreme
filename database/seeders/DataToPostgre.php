<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\ContestEdition;
use App\Player;
use DB;
use Schema;

class DataToPostgre extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $contest_editions = DB::connection('pgsql')->table('contest_edition')
                    ->select()->get();

        Schema::disableForeignKeyConstraints();
        ContestEdition::truncate();


        foreach ($contest_editions as $key => $contest_edition) {

            ContestEdition::create([
                'id' => $contest_edition->id,
                'name' => $contest_edition->name,
                'active' => $contest_edition->active,
                'created_at' => $contest_edition->created_at,
                'updated_at' => $contest_edition->updated_at,
            ]);

        }

        Schema::enableForeignKeyConstraints();

        Schema::disableForeignKeyConstraints();
        Player::truncate();

        $players = DB::connection('pgsql')->table('players')
                    ->select()->get();

        foreach ($players as $key => $player) {
            Player::create([
                'contest_id' => $player->contest_id,
                'country_id' => $player->country_id,
                'sk_player_id' => $player->sk_player_id,
                'team' => $player->team,
                'player_name' => $player->player_name,
                'player_age' => $player->player_age,
                'stamina' => $player->stamina,
                'pace' => $player->pace,
                'technique' => $player->technique,
                'passing' => $player->passing,
                'keeper' => $player->keeper,
                'defender' => $player->defender,
                'playmaker' => $player->playmaker,
                'striker' => $player->striker,
                'score' => $player->score,
                'active' => $player->active,
                'created_at' => $player->created_at,
                'updated_at' => $player->updated_at
            ]);
        }

        Schema::enableForeignKeyConstraints();
    }
}
