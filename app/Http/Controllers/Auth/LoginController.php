<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use Illuminate\Http\Request;
use App\{User, Country, Team};

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function sokker_login(Request $request)
    {
        $validator = \Validator::make(request()->all(), [
                        'name' => 'required',
                        'password' => 'required'
                    ]);


        $client = new Client([
            'cookies' => true
        ]);

        $response = $client->request('POST', 'https://sokker.org/start.php?session=xml', [
            'form_params' => [
                'ilogin' => $request->name,
                'ipassword' => $request->password
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

        $team_id = explode('=', $splitted_response[1])[1];

        $response = $client->request('GET', 'https://sokker.org/xml/team-'.$team_id.'.xml');
    
        $data = $this->loadXmlStringAsArray($response->getBody()->getContents());
        
        $user_info = $data['user'];
        $team_info = $data['team'];
        
        $user = User::find((int)$user_info['userID']);
       
        if(!$user){

            $country = Country::find($team_info['countryID']);

            $team = Team::firstOrCreate(
                [
                    'id' => $team_info['teamID']
                ],[
                    'team_name' => $team_info['name'],
                    'country_id' => $team_info['countryID'],
                    'region_id' => $team_info['regionID'],
                    'foundation_date' => $team_info['dateCreated'],
                    'rank' => $team_info['rank'],
                    'stadium_name' => $team_info['arenaName'], 
                    'money' => $country->getCurrentMoney($team_info['money']),
                    'fanclubCount' => $team_info['fanclubCount'],
                    'fanclubMood' => $team_info['fanclubMood'],
                    'juniorsMax' => $team_info['juniorsMax'],
                ]
            );

            $user = User::create([
                        'id' => (int)$user_info['userID'],
                        'team_id' => $team->id,
                        'name' => $user_info['login'],
                        'password' => bcrypt($request->password)
                    ]);
        }

        if (\Auth::attempt(['name' => $user->name, 'password' => $request->password])) {
            return  redirect()->route('home');
        } else {
            return redirect( '/' );
        }
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
