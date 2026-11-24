<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ContestEdition;
use App\Player;
use GuzzleHttp\Client;
use DB;

class UpdateXtreme extends Command
{
    private $stamina_table;
    private $pace_table;
    private $other_table;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:xtreme';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Xtreme competition';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->stamina_table = array(
            '1' => 0.1,
            '2' => 0.2,
            '3' => 0.3,
            '4' => 0.4,
            '5' => 0.5,
            '6' => 0.6,
            '7' => 0.7,
            '8' => 0.8,
            '9' => 1.0,
            '10' => 1.2,
            '11' => 1.5
        );

        $this->pace_table = array(
            '1' => 1.4,
            '2' => 1.7,
            '3' => 1.9,
            '4' => 2.2,
            '5' => 2.4,
            '6' => 2.6,
            '7' => 3.0,
            '8' => 3.4,
            '9' => 3.7,
            '10' => 4.1,
            '11' => 4.6,
            '12' => 5.0,
            '13' => 5.5,
            '14' => 6.0,
            '15' => 7.2,
            '16' => 8.4,
            '17' => 9.6,
            '18' => 11.5,
        );

        $this->other_table = array(
            '1' => 1.2,
            '2' => 1.4,
            '3' => 1.6,
            '4' => 1.8,
            '5' => 2.0,
            '6' => 2.2,
            '7' => 2.5,
            '8' => 2.8,
            '9' => 3.1,
            '10' => 3.4,
            '11' => 3.8,
            '12' => 4.2,
            '13' => 4.6,
            '14' => 5.0,
            '15' => 6.0,
            '16' => 7.0,
            '17' => 8.0,
            '18' => 9.5,
        );
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $active_edition = ContestEdition::where('active', true)->first();

        if (!$active_edition) {
            $this->warn('No hay una edición activa');
            \Log::info('update:xtreme - No hay una edición activa');
            return 0;
        }

        $players = DB::table('players')
            ->where('contest_id', $active_edition->id)
            ->where('active', true)
            ->distinct()
            ->pluck('sk_player_id');

        if ($players->isEmpty()) {
            $this->warn('No hay jugadores para actualizar en ' . $active_edition->name);
            \Log::info('update:xtreme - No hay jugadores para actualizar');
            return 0;
        }

        $client = new Client(['timeout' => 20]);
        $count = 0;
        $failed = [];

        foreach ($players as $sk_player_id) {
            try {
                $response = $client->request('GET', 'https://sokker.org/api/player/' . $sk_player_id);
                $data = json_decode((string)$response->getBody());

                if (!isset($data->info->skills->stamina)) {
                    $failed[] = $sk_player_id;
                    continue;
                }

                $new_player_info = new Player();
                $new_player_info->contest_id   = $active_edition->id;
                $new_player_info->sk_player_id = $sk_player_id;
                $new_player_info->stamina      = $data->info->skills->stamina;
                $new_player_info->keeper       = $data->info->skills->keeper;
                $new_player_info->pace         = $data->info->skills->pace;
                $new_player_info->defender     = $data->info->skills->defending;
                $new_player_info->technique    = $data->info->skills->technique;
                $new_player_info->playmaker    = $data->info->skills->playmaking;
                $new_player_info->passing      = $data->info->skills->passing;
                $new_player_info->striker      = $data->info->skills->striker;
                $new_player_info->player_name  = $data->info->name->full;
                $new_player_info->player_age   = $data->info->characteristics->age;
                $new_player_info->team         = $data->info->team->name;
                $new_player_info->country_id   = $data->info->country->code;

                $existing_record = Player::where([
                    'contest_id'   => $new_player_info->contest_id,
                    'sk_player_id' => $new_player_info->sk_player_id,
                    'player_name'  => $new_player_info->player_name,
                    'player_age'   => $new_player_info->player_age,
                    'team'         => $new_player_info->team,
                    'stamina'      => $new_player_info->stamina,
                    'keeper'       => $new_player_info->keeper,
                    'pace'         => $new_player_info->pace,
                    'defender'     => $new_player_info->defender,
                    'technique'    => $new_player_info->technique,
                    'playmaker'    => $new_player_info->playmaker,
                    'passing'      => $new_player_info->passing,
                    'striker'      => $new_player_info->striker,
                    'country_id'   => $new_player_info->country_id
                ])->first();

                if (!$existing_record) {
                    $last_player_record = Player::where('sk_player_id', $sk_player_id)
                        ->where('contest_id', $active_edition->id)
                        ->orderBy('created_at', 'desc')->first();

                    if (isset($last_player_record)) {
                        $new_player_info->score = $this->calculate_score($last_player_record, $new_player_info);
                    }
                    $new_player_info->save();
                    $count++;
                }
            } catch (\Throwable $e) {
                $failed[] = $sk_player_id;
                \Log::warning('update:xtreme - fallo jugador ' . $sk_player_id . ': ' . $e->getMessage());
            }
        }

        $message = 'Actualizados ' . $count . ' de ' . $players->count() . ' jugadores';
        if (count($failed)) {
            $message .= '. Fallaron ' . count($failed) . ': ' . implode(', ', $failed);
        }

        $this->info($message);
        \Log::info('update:xtreme - ' . $message);

        return count($failed) === $players->count() ? 1 : 0;
    }

    public function calculate_score($last_record, $current_record)
    {
        $last_score = $last_record->score;

        if ($current_record->stamina > $last_record->stamina) {

            $existing_stamina = Player::where([
                'stamina' => $current_record->stamina,
                'sk_player_id' => $current_record->sk_player_id
            ])->first();

            if (!$existing_stamina) {
                for ($i = $last_record->stamina + 1; $i <= $current_record->stamina; $i++) {
                    $last_score += $this->stamina_table[$i];
                }
            }
        }

        if ($current_record->pace > $last_record->pace) {

            for ($i = $last_record->pace + 1; $i <= $current_record->pace; $i++) {
                $last_score += $this->pace_table[$i];
            }
        }
        //technique
        if ($current_record->technique > $last_record->technique) {

            for ($i = $last_record->technique + 1; $i <= $current_record->technique; $i++) {
                $last_score += $this->other_table[$i];
            }
        }
        //passing
        if ($current_record->passing > $last_record->passing) {

            for ($i = $last_record->passing + 1; $i <= $current_record->passing; $i++) {
                $last_score += $this->other_table[$i];
            }
        }
        //keeper
        if ($current_record->keeper > $last_record->keeper) {

            for ($i = $last_record->keeper + 1; $i <= $current_record->keeper; $i++) {
                $last_score += $this->other_table[$i];
            }
        }
        //defender
        if ($current_record->defender > $last_record->defender) {

            for ($i = $last_record->defender + 1; $i <= $current_record->defender; $i++) {
                $last_score += $this->other_table[$i];
            }
        }
        //playmaker
        if ($current_record->playmaker > $last_record->playmaker) {

            for ($i = $last_record->playmaker + 1; $i <= $current_record->playmaker; $i++) {
                $last_score += $this->other_table[$i];
            }
        }
        //striker
        if ($current_record->striker > $last_record->striker) {

            for ($i = $last_record->striker + 1; $i <= $current_record->striker; $i++) {
                $last_score += $this->other_table[$i];
            }
        }

        return $last_score;
    }
}
