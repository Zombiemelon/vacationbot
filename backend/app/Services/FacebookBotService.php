<?php


namespace App\Services;

use App\Interfaces\BotInterface;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Exception;

class FacebookBotService implements BotInterface
{
    public function sendPhoto(string $chat_id, string $photo, string $caption = '')
    {
        $facebookAPIToken = env('FACEBOOK_BOT_API_TOKEN');
        $facebookApiUrl = env('FACEBOOK_BOT_API_URL');
        $url = "$facebookApiUrl/me/messages";
        $client = new Client();
        $response = $client->request('POST', $url, [
            'json' => [
                "access_token" => $facebookAPIToken,
                "recipient" => [
                    "id" => $chat_id
                ],
                "message" => [
                    "attachment" => [
                    "type" => "template",
                    "payload" => [
                        "template_type" => "generic",
                      "elements" => [[
                            "title" => $caption,
                            "image_url" => $photo,
                      ]]
                    ]
                  ]
                ],
                "messaging_type" => "MESSAGE_TAG",
                "tag" => "CONFIRMED_EVENT_UPDATE"
            ]
        ]);
        return $response->getStatusCode();
    }

    public function sendMessage(string $chat_id, string $message = '', string $keyboard = 'missing'): int
    {
        $facebookAPIToken = env('FACEBOOK_BOT_API_TOKEN');
        $facebookApiUrl = env('FACEBOOK_BOT_API_URL');
        $url = "$facebookApiUrl/me/messages";
        $client = new Client();
        $response = $client->request('POST', $url, [
            'json' => [
                "access_token" => $facebookAPIToken,
                "recipient" => [
                    "id" => $chat_id
                ],
                "message" => [
                    "text" => $message
                ],
                "messaging_type" => "MESSAGE_TAG",
	            "tag" => "CONFIRMED_EVENT_UPDATE"
            ]
        ]);
        return $response->getStatusCode();
    }

    public function getInlineButtons($buttons)
    {

    }

    public function getStopButtonsByVacationsList($vacationsList)
    {

    }

    public function getCommand(string $text):string
    {
        $splitText = explode(' ',$text);
        $splitText = explode('@',$splitText[0]);
        $command = $splitText[0];
        return $command;
    }

    /**
     * @param Request $request
     * @return string
     * @throws Exception
     */
    public function getChatId(Request $request): string
    {
        return $request['entry'][0]['messaging'][0]['sender']['id'];
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getText(Request $request): string
    {
        return $request['entry'][0]['messaging'][0]['message']['text'];
    }

    /**
     * @param string $firstLine
     * @param string $secondLine
     * @return string
     */
    public function getPhotoCaption(string $firstLine, string $secondLine): string
    {
        return $firstLine;
    }
}
