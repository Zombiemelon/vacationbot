<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Crew\Unsplash;
use GuzzleHttp\Client;

class VacationImageController extends Controller
{
    public function getVacationImage(Request $request)
    {
        Unsplash\HttpClient::init([
            'applicationId'	=> 'b575bc605a85f13085a19dd18c9af7ee771d250f994de993fc358a258e5af864',
            'secret'		=> 'abed1757c6141be68d48078c4e6576975b5cfc02af298a3aa3f946fe48104df0',
            'callbackUrl'	=> 'urn:ietf:wg:oauth:2.0:oob',
            'utmSource' => 'vacation_bot'
        ]);
        $filters = [
            'featured' => true,
            'query'    => 'bali',
            'w'        => 100,
            'h'        => 100
        ];

        $photo = Unsplash\Photo::random($filters);
        $photo = $photo->links['html'];
        $chat_id = $request['message']['chat']['id'] ? $request['message']['chat']['id'] : '-336623041';
        $caption = "78 days";
        $url = "https://api.telegram.org/bot1036050185:AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ/sendPhoto?parse_mode=html?chat_id=$chat_id&photo=$photo&caption=$caption";
        $client = new Client();
        $response =$client->request('GET',$url);
        return $response->getStatusCode();
    }
}
