<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ContestEdition;
use App\Player;
use GuzzleHttp\Client;
use DB;

class UpdateXtreme extends Command
{
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
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {

            $active_edition = ContestEdition::where('active', true)->first();

            $query = "SELECT DISTINCT sk_player_id FROM players where contest_id = '{$active_edition->id}' AND active = true";

            $result = DB::select($query);

            if (count($result) > 0) {

                $client = new Client();
                $count = 0;
                foreach ($result as $key => $value) {

                    $response = $client->request('GET', 'https://sokker.org/player/PID/' . $value->sk_player_id);

                    $domDoc = new \DOMDocument();

                    $html = (string)$response->getBody();

                    $domHtml = @$domDoc->loadHTML($html);

                    $xpath = new \DOMXPath($domDoc);

                    $results = $xpath->query("//*[@class='skillNameNumber']");

                    if ($results->length > 2) {

                        $new_player_info = new Player();

                        $array = ['[', ']'];

                        $new_player_info->contest_id = $active_edition->id;
                        $new_player_info->sk_player_id = $value->sk_player_id;
                        $new_player_info->stamina     = str_replace($array, '', $results[2]->nodeValue);
                        $new_player_info->keeper      = str_replace($array, '', $results[3]->nodeValue);
                        $new_player_info->pace         = str_replace($array, '', $results[4]->nodeValue);
                        $new_player_info->defender     = str_replace($array, '', $results[5]->nodeValue);
                        $new_player_info->technique = str_replace($array, '', $results[6]->nodeValue);
                        $new_player_info->playmaker = str_replace($array, '', $results[7]->nodeValue);
                        $new_player_info->passing     = str_replace($array, '', $results[8]->nodeValue);
                        $new_player_info->striker     = str_replace($array, '', $results[9]->nodeValue);

                        $classname = "panel-heading";
                        $results = $xpath->query("//*[contains(@class, '$classname')]");

                        //Name
                        $a_tags = $results[0]->getElementsByTagName('a');
                        $new_player_info->player_name = trim($a_tags[0]->textContent);

                        //Age
                        $age_tag = $results[0]->getElementsByTagName('strong');
                        $new_player_info->player_age = (int)$age_tag[0]->textContent;

                        //Team and country_id
                        $classname = "list-unstyled list-underline";
                        $results = $xpath->query("//*[contains(@class, '$classname')]");

                        $a_tags = $results[0]->getElementsByTagName('a');
                        $new_player_info->team = $a_tags[0]->textContent;
                        $new_player_info->country_id = (int) filter_var($a_tags[1]->getAttribute('href'), FILTER_SANITIZE_NUMBER_INT);

                        $existing_record = Player::where([
                            'contest_id' => $new_player_info->contest_id,
                            'sk_player_id' => $new_player_info->sk_player_id,
                            'player_name' => $new_player_info->player_name,
                            'player_age' => $new_player_info->player_age,
                            'team' => $new_player_info->team,
                            'stamina' => $new_player_info->stamina,
                            'keeper' => $new_player_info->keeper,
                            'pace' => $new_player_info->pace,
                            'defender' => $new_player_info->defender,
                            'technique' => $new_player_info->technique,
                            'playmaker' => $new_player_info->playmaker,
                            'passing' => $new_player_info->passing,
                            'striker' => $new_player_info->striker,
                            'country_id' => $new_player_info->country_id
                        ])->first();

                        if (!$existing_record) {
                            $last_player_record = Player::where('sk_player_id', $new_player_info->sk_player_id)
                                ->where('contest_id', $active_edition->id)
                                ->orderBy('created_at', 'desc')->first();

                            if (isset($last_player_record)) {
                                $new_player_info->score = $this->calculate_score($last_player_record, $new_player_info);
                            }
                            $new_player_info->save();
                            $count++;
                        }
                    }
                }
                \Log::info('Actualizados ' . $count . ' jugadores');
            } else {
                \Log::info('No hay jugadores para actualizar');
            }

        } catch (\Exception $e) {
            \Log::info($e->getMessage());
        }
    }
}
