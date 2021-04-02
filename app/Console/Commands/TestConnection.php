<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;

class TestConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:conn';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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

        $client = new Client();

        $response = $client->request('GET', 'https://sokker.org/player/PID/37634408');

        $domDoc = new \DOMDocument();

        $html = (string)$response->getBody();

        $domHtml = @$domDoc->loadHTML($html);

        $domDoc->preserveWhiteSpace = false;

        $tables = $domDoc->getElementsByTagName('table');
        $spans = $tables[0]->getElementsByTagName('span');
        foreach ($spans as $key => $value) {
            var_dump($value);
        }
        dd('a');
        foreach ($tables[0] as $key => $value) {
            $trs = $value->getElementsByTagName('span');
            foreach ($trs as $key => $tr) {
                $span = $tr->getElementsByTagName('span');
                dd($span);
            }
        }
        $rows = $tables->item(0)->getElementsByTagName('tr');

       foreach ($rows as $row)
      {
       // get each column by tag name
          $columns = $row->getElementsByTagName('td');
       // echo the values  
          echo $columns->item(0)->nodeValue.'<br />';
          echo $columns->item(1)->nodeValue.'<br />';
          echo $columns->item(2)->nodeValue;
        }

        dd('a');
       

        $url = 'https://sokker.org/start.php?session=xml';
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
        dd($result);
        if ($result === FALSE) { 
            dd('error'); 
        } else {
            /*$split = explode('=', $result);
            $id = $split[1];

            $data = Http::get('https://sokker.org/xml/countries.xml');
            //dd($data);

            //$ob= simplexml_load_string($xmlfile);*/
            dd('a');
        }

        
    }

    function element_to_obj($element) {
    $obj = array( "tag" => $element->tagName );
    foreach ($element->attributes as $attribute) {
        $obj[$attribute->name] = $attribute->value;
    }
    foreach ($element->childNodes as $subElement) {
        if ($subElement->nodeType == XML_TEXT_NODE) {
            $obj["html"] = $subElement->wholeText;
        }
        else {
            $obj["children"][] = element_to_obj($subElement);
        }
    }
    return $obj;
}
}
