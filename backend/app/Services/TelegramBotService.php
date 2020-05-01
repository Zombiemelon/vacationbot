<?php


namespace App\Services;

use App\Interfaces\BotInterface;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Exception;

class TelegramBotService implements BotInterface
{
    public function sendPhoto(string $chat_id, string $photo, string $caption = '')
    {
        $api_url = env('BOT_API');
        $caption = urlencode($caption);
        $photo = urlencode($photo);
        $url = "$api_url/sendPhoto?chat_id=$chat_id&parse_mode=HTML&photo=$photo&caption=$caption";
        $client = new Client();
        $response = $client->request('GET',$url);
        return $response->getStatusCode();
    }

    public function sendMessage(string $chat_id, string $message = '', string $keyboard = 'missing'): int
    {
        $api_url = env('BOT_API');
        $url = "$api_url/sendMessage?chat_id=$chat_id&parse_mode=HTML&text=$message";
        $keyboard == 'missing' ? '' : $url.= "&reply_markup=$keyboard";
        $client = new Client();
        $response = $client->request('GET',$url);
        return $response->getStatusCode();
    }

    public function getInlineButtons($buttons)
    {
        $keyboard = ["inline_keyboard" => []];
        $keyboard["inline_keyboard"][0] = [];
        foreach ($buttons as $index => $button) {
            $text = $button["text"];
            $callbackData = $button["callbackData"];
            $keyboard["inline_keyboard"][0][] = ["text" => $text, "callback_data" => $callbackData];
        }
        return json_encode($keyboard);
    }

    public function getStopButtonsByVacationsList($vacationsList)
    {
        $buttons = [];
        foreach ($vacationsList as $vacation) {
            $destination = $vacation->destination;
            $vacationDate = $vacation->vacation_date;
            $vacationId = $vacation->id;
            $buttons[] = ["text" => "$destination $vacationDate", "callbackData" => "$vacationId"];
        }
        return $buttons;
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
        if($request['callback_query']) {
            return $request['callback_query']['message']['chat']['id'];
        } elseif ($request['message']) {
            return $request['message']['chat']['id'];
        } elseif ($request['edited_message']) {
            return $request['edited_message']['chat']['id'];
        }
        throw new Exception('Chat not found', 400);
    }

    /**
     * @param Request $request
     * @return string
     */
    public function getText(Request $request): string
    {
        if($request['callback_query']) {
            return $request['callback_query']["data"];
        } elseif ($request['message'] && array_key_exists('text', $request['message'])) {
            return $request['message']['text'];
        } elseif ($request['edited_message'] && array_key_exists('text', $request['edited_message'])) {
            return $request['edited_message']['text'];
        }
        return '';
    }

    /**
     * @param string $message
     * @param int $callbackQueryId
     * @return int
     */
    public function replyToQuery(string $message, int $callbackQueryId)
    {
        $api_url = env('BOT_API');
        $url = "$api_url/answerCallbackQuery?callback_query_id=$callbackQueryId&text=$message";
        $client = new Client();
        $response = $client->request('GET',$url);
        return $response->getStatusCode();
    }

    /**
     * @param string $firstLine
     * @param string $secondLine
     * @return string
     */
    public function getPhotoCaption(string $firstLine, string $secondLine): string
    {
        return $firstLine.$secondLine;
    }
}
