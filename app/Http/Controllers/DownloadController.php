<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use App\{User, Country, Team, TeamPlayer, PlayerHistory};

class DownloadController extends Controller
{
    public function show_form()
    {
    	return view('download.form');
    }

    public function download_data(Request $request)
    {
    	$client = new Client([
    		'cookies' => true
    	]);

    	$response = $client->request('POST', 'https://sokker.org/start.php?session=xml', [
		    'form_params' => [
		        'ilogin' => $request->ilogin,
		        'ipassword' => $request->ipassword
		    ]
		]);

		$splitted_response = explode(' ', $response->getBody()->getContents());

		if(strlen($splitted_response[0]) > 2){
			$error = explode('=', $splitted_response[1]);
			switch ($error[1]) {
				case '1':
					$message = 'La contraseña es incorrecta';
					break;
				case '3':
					$message = 'El usuario no tiene ningún equipo';
					break;
				case '4':
					$message = 'El usuario fue baneado';
					break;
				case '5':
					$message = 'El equipo está en bancarrota';
					break;
				case '6':
					$message = 'El IP está en lista negra';
					break;
			}
			return view('download.form')->with(array(
				'message' => $message,
				'class' => 'danger' 
			));
		} else {

			$response = $client->request('GET', 'https://sokker.org/xml/players-102306.xml');
			
			$result = $this->loadXmlStringAsArray($response->getBody()->getContents());
			dd($result);


			//$team_id = explode('=', $splitted_response[1])[1];

			//$response = $client->request('GET', 'https://sokker.org/xml/team-'.$team_id.'.xml');
			

			//https://sokker.org/xml/

		}

		
		dd('a');

		//https://sokker.org/transferSearch/trainer/0/pg/1/age_min/17/age_max/20/nationality/97/transfer_list/2/sort/end

    	/*$url = 'https://sokker.org/start.php?session=xml';
        $data = array('ilogin' => $request->ilogin, 'ipassword' => $request->ipassword);

        // use key 'http' even if you send the request to https://...
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
       
        $result = file_get_contents($url, false, $context);

        return view('download.result', compact('result'));*/
        /*foreach($_COOKIE as $v){
		  echo htmlentities($v, 3, 'UTF-8').'<br />';
		}*/
		/*echo htmlentities($_COOKIE['XMLSESSID'], 3, 'UTF-8');
        dd('a');*/
    }

    public function download_players(Request $request)
    {
    	$validator = \Validator::make(request()->all(), [
                        'name' => 'required',
                        'ipassword' => 'required'
                    ]);

    	$client = new Client([
            'cookies' => true
        ]);

        $response = $client->request('POST', 'https://sokker.org/start.php?session=xml', [
            'form_params' => [
                'ilogin' => $request->name,
                'ipassword' => $request->ipassword
            ]
        ]);

        $splitted_response = explode(' ', $response->getBody()->getContents());

        if(strlen($splitted_response[0]) > 2){
            $error = explode('=', $splitted_response[1]);
            switch ($error[1]) {
                case '1':
                    $message = 'La contraseña es incorrecta';
                    $validator->after(function($validator) use ($message){
                       $validator->errors()->add('password', $message);
                    });
                    break;
                case '3':
                    $message = 'El usuario no tiene ningún equipo';
                    $validator->after(function($validator) use ($message){
                       $validator->errors()->add('name', $message);
                    });
                    break;
                case '4':
                    $message = 'El usuario fue baneado';
                    $validator->after(function($validator) use ($message){
                       $validator->errors()->add('name', $message);
                    });
                    break;
                case '5':
                    $message = 'El equipo está en bancarrota';
                    $validator->after(function($validator) use ($message){
                       $validator->errors()->add('name', $message);
                    });
                    break;
                case '6':
                    $message = 'El IP está en lista negra';
                    $validator->after(function($validator) use ($message){
                       $validator->errors()->add('name', $message);
                    });
                    break;
            }
        }

    	if ($validator->fails()) {
            return redirect()
                    ->back()
                    ->withInput()
                    ->withErrors( $validator->errors() );
        }

        $user = auth()->user();

        $response = $client->request('GET', 'https://sokker.org/xml/players-'.$user->team_id.'.xml');
    
        $players = $this->loadXmlStringAsArray($response->getBody()->getContents())['player'];

        foreach ($players as $key => $record) {
        	$player = TeamPlayer::updateOrCreate(
			    [
			    	'id' => $record['ID'],
			    	'name' => $record['name'],
			    	'surname' => $record['surname']
			    ],[
			    	'age' => $record['age'], 
			    	'country_id' => $record['countryID'], 
			    	'team_id' => $user->team_id, 
			    	'height' => $record['height'],
			    	'weight' => $record['weight'],
			    	'BMI' => $record['BMI'],
			    	'teamID' => $record['teamID'],
			    	'value' => $record['value'],
			    	'wage' => $record['wage'],
			    	'goals' => $record['goals'],
			    	'assists' => $record['assists'],
			    	'matches' => $record['matches'],
			    	'ntGoals' => $record['ntGoals'],
			    	'ntMatches' => $record['ntMatches'],
			    	'ntAssists' => $record['ntAssists'],
			    	'injuryDays' => $record['injuryDays'],
			    	'national' => $record['national'] == 0 ? false : true,
			    ]
			);

        	PlayerHistory::create([
        		'player_id' => $player->id,
        		'skillForm' => $record['skillForm'],
        		'skillExperience' => $record['skillExperience'],
        		'skillTeamwork' => $record['skillTeamwork'],
        		'skillDiscipline' => $record['skillDiscipline'],
        		'skillStamina' => $record['skillStamina'],
        		'skillPace' => $record['skillPace'],
        		'skillTechnique' => $record['skillTechnique'],
        		'skillPassing' => $record['skillPassing'],
        		'skillKeeper' => $record['skillKeeper'],
        		'skillDefending' => $record['skillDefending'],
        		'skillPlaymaking' => $record['skillPlaymaking'],
        		'skillScoring' => $record['skillScoring']
        	]);
        }

        $user->updated_at = \Carbon\Carbon::now()->toDateTimeString();

        return redirect()->route('home');

    }

    public function loadXmlStringAsArray($xmlString)
    {
        $array = (array) @simplexml_load_string($xmlString);
        if(!$array){
            $array = (array) @json_decode($xmlString, true);
        } else{
            $array = (array)@json_decode(json_encode($array), true);
        }
        return $array;
    }
}
