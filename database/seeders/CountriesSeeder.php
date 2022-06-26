<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use App\{Country, Region};

class CountriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $client = new Client([
    		'cookies' => true
    	]);

    	$response = $client->request('POST', 'https://sokker.org/start.php?session=xml', [
		    'form_params' => [
		        'ilogin' => 'napoles',
		        'ipassword' => 'warapo.com'
		    ]
		]);

		$splitted_response = explode(' ', $response->getBody()->getContents());

		if(strlen($splitted_response[0]) == 2){

			$response = $client->request('GET', 'https://sokker.org/xml/countries.xml');
			$result = $this->loadXmlStringAsArray($response->getBody()->getContents());

			if( count($result['country']) != Country::count() ){
				foreach ($result['country'] as $key => $country) {
					$country = Country::firstOrCreate(
									[
										'id' => $country['countryID']
									],[
										'name' => $country['name'],
										'currencyRate' => $country['currencyRate']
									]
								);

					$response = $client->request('GET', 'https://sokker.org/xml/regions-'.$country->id.'.xml');
					$result = $this->loadXmlStringAsArray($response->getBody()->getContents());

					if( count($result['region']) != Region::where('country_id', $country->id)->count() ) {
						foreach ($result['region'] as $key => $region) {
							Region::firstOrCreate(
								[
									'id' => $region['regionID']
								],[
									'country_id' => $country->id,
									'name' => $region['name']
								]
							);
						}
					}
				}
			}
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
