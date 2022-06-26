<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Player;
use App\ContestEdition;
use DB;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class PlayerUpdateController extends Controller
{
    private $stamina_table;
    private $pace_table;
    private $other_table;

    public function __construct()
    {
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

    public function show_insert()
    {
        if(\Auth::guard('system_users')->check()){
            return view('players.show');
        } else {
            return redirect()->back();
        }

    }

    public function store_by_id(Request $request)
    {
        try {

            $active_edition = ContestEdition::where('active', true)->first();

            $client = new Client();

            $response = $client->request('GET', 'https://sokker.org/player/PID/' . $request->player_id);


            $domDoc = new \DOMDocument();

            $html = (string)$response->getBody();

            if (strlen($html) > 0) {

                $domHtml = @$domDoc->loadHTML($html);

                $xpath = new \DOMXPath($domDoc);

                $results = $xpath->query("//*[@class='skillNameNumber']");

                if ($results->length > 2) {

                    $new_player_info = new Player();

                    $array = ['[', ']'];

                    $new_player_info->contest_id = ContestEdition::where('active', true)->first()->id;
                    $new_player_info->sk_player_id = $request->player_id;
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

                    if ($existing_record) {
                        return view('players.show')->with(array(
                            'message_id' => 'Ya existen estos datos para este jugador',
                            'error' => true
                        ));
                    }

                    $last_player_record = Player::where('sk_player_id', $new_player_info->sk_player_id)
                        ->where('contest_id', $active_edition->id)
                        ->orderBy('created_at', 'desc')->first();

                    if (isset($last_player_record)) {
                        $new_player_info->score = $this->calculate_score($last_player_record, $new_player_info);
                    }
                    $new_player_info->save();

                    return view('players.show')->with(array(
                        'message_id' => 'Datos del jugador ' . $new_player_info->player_name . ' insertados correctamente'
                    ));
                } else {
                    return view('players.show')->with(array(
                        'message_id' => 'El jugador no está en anuncio de transferencia',
                        'error' => true
                    ));
                }
            }
            return view('players.show')->with(array(
                'message_id' => 'No existe el jugador',
                'error' => true
            ));
        } catch (Exception $e) {
            return view('players.show')->with(array(
                'message_id' => 'Error ' . $e->getMessage()
            ));
        }
    }

    public function update_all(Request $request)
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
                return view('players.show')->with(array(
                    'message_id' => 'Actualizados ' . $count . ' jugadores'
                ));
            }
            return view('players.show')->with(array(
                'message_id' => 'No hay jugadores registrados en la Edición ' . $active_edition->name
            ));
        } catch (Exception $e) {
            dd($e);
            return view('players.show')->with(array(
                'message_id' => 'Error ' . $e->getMessage()
            ));
        }
    }

    public function store(Request $request)
    {
        try {

            $request->validate([
                'data' => 'required'
            ]);

            $text = trim($request->data);
            $textAr = explode("\n", $text);
            $textAr = array_filter($textAr, 'trim');
            //dd($textAr);
            $new_player_info = new Player();
            $new_player_info->contest_id = ContestEdition::where('active', true)->first()->id;

            foreach ($textAr as $key => $line) {

                switch ($key) {
                    case '0':
                        //Take ID and player name
                        $split = explode(',', $line);

                        //[b][pid=37628887]Ottone Abacciolo[/pid][/b]
                        $ipos = strpos($split[0], '=') + 1;
                        $substring = substr($split[0], $ipos);

                        //37628887]Ottone Abacciolo[/pid][/b]
                        $fpos = strpos($substring, ']');

                        //Take Sokker ID
                        $new_player_info->sk_player_id = substr($substring, 0, $fpos);

                        $ipos = strpos($substring, ']') + 1;
                        $substring = substr($substring, $ipos);

                        //Ottone Abacciolo[/pid][/b]
                        $fpos = strpos($substring, '[');
                        $new_player_info->player_name = substr($substring, 0, $fpos);

                        //Take player age
                        //" age:[b]19[/b]"
                        $ipos = strpos($split[1], ']') + 1;
                        $substring = substr($split[1], $ipos);
                        $new_player_info->player_age =  (int)substr($substring, 0, 2);

                        break;

                    case '1':

                        $split = explode(',', $line);

                        $ipos = strpos($split[0], ']') + 1;
                        $substring = substr($split[0], $ipos);

                        $name = str_replace('[/tid]', '', $substring);

                        //Take Team
                        $new_player_info->team = $name;

                        //Get Country ID

                        $ipos = strpos($split[1], '/') + 1;
                        $substring = substr($split[1], $ipos);

                        $ipos = strpos($substring, '/') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->country_id = substr($substring, 0, $fpos);

                        break;

                    case '6':

                        $split = explode(',', $line);

                        //[b]poor [3][/b] stamina
                        $ipos = strpos($split[0], '[') + 1;
                        $substring = substr($split[0], $ipos);

                        $ipos = strpos($substring, '[') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->stamina = (int)substr($substring, 0, $fpos);

                        //[b]tragic [0][/b] keeper
                        $ipos = strpos($split[1], '[') + 1;
                        $substring = substr($split[1], $ipos);

                        $ipos = strpos($substring, '[') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->keeper = (int)substr($substring, 0, $fpos);

                        break;
                    case '7':

                        $split = explode(',', $line);

                        //[b]formidable [11][/b] pace
                        $ipos = strpos($split[0], '[') + 1;
                        $substring = substr($split[0], $ipos);

                        $ipos = strpos($substring, '[') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->pace = (int)substr($substring, 0, $fpos);

                        //[b]excellent [10][/b] defender
                        $ipos = strpos($split[1], '[') + 1;
                        $substring = substr($split[1], $ipos);

                        $ipos = strpos($substring, '[') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->defender = (int)substr($substring, 0, $fpos);

                        break;

                    case '8':

                        $split = explode(',', $line);

                        //[b]solid [8][/b] technique
                        $ipos = strpos($split[0], '[') + 1;
                        $substring = substr($split[0], $ipos);

                        $ipos = strpos($substring, '[') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->technique = (int)substr($substring, 0, $fpos);

                        //[b]poor [3][/b] playmaker
                        $ipos = strpos($split[1], '[') + 1;
                        $substring = substr($split[1], $ipos);

                        $ipos = strpos($substring, '[') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->playmaker = (int)substr($substring, 0, $fpos);

                        break;
                    case '9':

                        $split = explode(',', $line);

                        //[b]unsatisfactory [2][/b] passing
                        $ipos = strpos($split[0], '[') + 1;
                        $substring = substr($split[0], $ipos);

                        $ipos = strpos($substring, '[') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->passing = (int)substr($substring, 0, $fpos);

                        //[b]unsatisfactory [2][/b] striker
                        $ipos = strpos($split[1], '[') + 1;
                        $substring = substr($split[1], $ipos);

                        $ipos = strpos($substring, '[') + 1;
                        $substring = substr($substring, $ipos);

                        $fpos = strpos($substring, ']');
                        $new_player_info->striker = (int)substr($substring, 0, $fpos);

                        break;
                    default:
                        break;
                }
            }

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

            if ($existing_record) {
                return view('players.show')->with(array(
                    'message' => 'Ya existen estos datos para este jugador',
                    'error' => true
                ));
            }


            $last_player_record = Player::where('sk_player_id', $new_player_info->sk_player_id)
                ->orderBy('created_at', 'desc')->first();

            if (isset($last_player_record)) {
                $new_player_info->score = $this->calculate_score($last_player_record, $new_player_info);
            }
            $new_player_info->save();

            return view('players.show')->with(array(
                'message' => 'Datos del jugador ' . $new_player_info->player_name . ' insertados correctamente'
            ));
        } catch (Exception $e) {
            dd($e);
            return view('players.show')->with(array(
                'message' => 'Error ' . $e->getMessage()
            ));
        }
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

    public function show_ranking()
    {
        $view_data['active_tab'] = 'ranking_five';

        $contest_id = ContestEdition::where('active', true)->first()->id;

        $query = "SELECT MAX(players.score) as max_score, players.player_name, players.team,
    			  players.sk_player_id, players.player_age
    			  FROM players
                  WHERE players.contest_id = {$contest_id} AND players.active = true
    			  GROUP BY players.player_name, players.sk_player_id, players.team, players.player_age
    			  ORDER BY max_score DESC";


        $view_data['data'] = collect(DB::select($query));

        //Inactive
        $query = "SELECT MAX(players.score) as max_score, players.player_name, players.team,
    			  players.sk_player_id, players.player_age
    			  FROM players
                  WHERE players.contest_id = {$contest_id} AND players.active = false
    			  GROUP BY players.player_name, players.sk_player_id, players.team, players.player_age
    			  ORDER BY max_score DESC";


        $view_data['inactive_data'] = collect(DB::select($query));

        $query = "SELECT MAX(players.score) as max_score, players.sk_player_id
    			  FROM players
                  WHERE players.contest_id = {$contest_id} AND players.active = true
    			  GROUP BY players.sk_player_id
    			  ORDER BY max_score DESC LIMIT 5";

        $elements = DB::select($query);

        $first_five = collect();
        foreach ($elements as $key => $item) {
            $element = Player::where('sk_player_id', $item->sk_player_id)
                ->where('contest_id', $contest_id)
                ->orderBy('created_at', 'desc')->get();

            if ($element->count() != 0) {
                $first = $element->first();
                $last = $element->last();

                $diff = new \Stdclass();
                $diff->diff_stamina = $first->stamina - $last->stamina;
                $diff->diff_keeper = $first->keeper - $last->keeper;
                $diff->diff_pace = $first->pace - $last->pace;
                $diff->diff_defender = $first->defender - $last->defender;
                $diff->diff_technique = $first->technique - $last->technique;
                $diff->diff_playmaker = $first->playmaker - $last->playmaker;
                $diff->diff_passing = $first->passing - $last->passing;
                $diff->diff_striker = $first->striker - $last->striker;

                $first->differences = $diff;

                $first_five->push($first);
            }
        }

        $view_data['first_five'] = $first_five;

        $view_data['active_edition'] = ContestEdition::where('active', true)->first();

        return view('ranking.show', compact('view_data'));
    }

    public function destroy($id)
    {
        try {
            $player = Player::find($id);
            $sk_id = $player->sk_player_id;
            $player->delete();
            return redirect()->route('show_player', $sk_id)->with(array(
                'message' => 'Registro eliminado correctamente'
            ));
        } catch (Exception $e) {
            return redirect()->back()->with(array(
                'message' => 'No se pudo eliminar el registro'
            ));
        }
    }

    public function show_player($sokker_id)
    {
        $contest_id = ContestEdition::where('active', true)->first()->id;
        $view_data['data'] = Player::where('sk_player_id', $sokker_id)
            ->where('contest_id', $contest_id)
            ->orderBy('created_at', 'asc')
            ->get();

        $view_data['lastRecord'] = Player::where('sk_player_id', $sokker_id)
                                        ->where('contest_id', $contest_id)
                                        ->orderBy('created_at', 'desc')
                                        ->first();


        return view('players.player_details', compact('view_data'))->with(array(
            'message_id' => session('message_id'),
            'error' => session('error'),
        ));
    }

    public function reference_table()
    {
        return view('reference_table.table');
    }

    public function delete_all($sk_id)
    {
        try {
            $contest_id = ContestEdition::where('active', true)->first()->id;
            $player_name = Player::where('sk_player_id', $sk_id)->first(['player_name']);

            Player::where('sk_player_id', $sk_id)->where('contest_id', $contest_id)->delete();

            return redirect()->route('show_ranking')->with(array(
                'message' => 'Los registros del jugador ' . $player_name->player_name . ' fueron eliminados'
            ));
        } catch (Exception $e) {
            return redirect()->back()->with(array(
                'message' => 'Hubo un error al eliminar los datos'
            ));
        }
    }

    public function change_active(Request $request, $sk_id)
    {
        try {
            $contest_id = ContestEdition::where('active', true)->first()->id;
            $player_name = Player::where('sk_player_id', $sk_id)->first();

            Player::where('sk_player_id', $sk_id)
                  ->where('contest_id', $contest_id)
                  ->update(['active' => !$request->active]);

            $status = $request->active == 1 ? 'inactivado' : 'activado';

            return redirect()->route('show_player', $player_name->sk_player_id)->with(array(
                'message_id' => 'El jugador ' . $player_name->player_name . ' fue '.$status,
                'error' => false
            ));
        } catch (\Exception $e) {
            return redirect()->back()->with(array(
                'message_id' => 'Hubo un error cambiar el estado del jugador',
                'error' => true
            ));
        }
    }
}
