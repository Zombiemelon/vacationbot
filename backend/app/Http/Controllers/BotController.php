<?php

namespace App\Http\Controllers;

use App\Chat;
use App\ChatStatus;
use App\Services\MatchServices\BotMatchService;
use App\Services\MessageGenerationService;
use App\Services\PhotoDownloadService;
use App\Vacation;
use Exception;

use Illuminate\Http\Request;

class BotController extends Controller
{
    public const VACATION = 1;
    public const SELECT_DATE = 2;
    public const COMPLETED = 3;
    public const STOP = 4;
    private $telegramBotService;
    private $vacation;
    private $photoDownloadService;
    private $messageGenerationService;
    private $botMatchService;

    public function __construct(Vacation $vacation, PhotoDownloadService $photoDownloadService, MessageGenerationService $messageGenerationService, BotMatchService $botMatchService
    )
    {
        $this->vacation = $vacation;
        $this->photoDownloadService = $photoDownloadService;
        $this->messageGenerationService = $messageGenerationService;
        $this->botMatchService = $botMatchService;
    }

    public function vacation(Request $request)
    {
        $route = $this->botMatchService->getRouteName($request);
        $this->telegramBotService = $this->botMatchService->getBot($route);
        try {
            $text = $this->telegramBotService->getText($request);
            $splitText = explode(' ',$text);
            $command = $this->telegramBotService->getCommand($text);
            $chat_id = $this->telegramBotService->getChatId($request);
            $destination = array_key_exists(1,$splitText) ? $splitText[1] : '';
            $date = array_key_exists(2,$splitText) ? $splitText[2] : '';
            try {
                $language = $request['message']['from']['language_code'] ? $request['message']['from']['language_code'] : '';
            } catch (Exception $e) {
                $language = 'en';
            }
            $chat = Chat::where('telegram_chat_id', $chat_id)->first();
            if (isset($chat) && $chat->chat_status_id == self::VACATION &&
                !$this->vacation->getVacationByChatDestinationDate($chat_id, $destination, $date)) {
                $destination = $text;
                try {
                    $this->photoDownloadService->getPhotoByDestination($destination);
                    $this->vacation->createInitialVacation($destination, $chat_id);
                    $chat->updateState(ChatStatus::SELECT_DATE);
                    $message = $this->messageGenerationService->getSelectDateMessage($language);
                } catch (Exception $exception) {
                    $message = $this->messageGenerationService->getIncorrectDestinationMessage($exception);
                }
                $this->telegramBotService->sendMessage($chat_id, $message);
                return response("Now select the date", 200);
            } elseif (isset($chat) && $chat->chat_status_id == ChatStatus::SELECT_DATE) {
                try {
                    $vacation = $chat->vacations()->orderBy('id','desc')->first();
                    $vacation->vacation_date = $text;
                    $destination = $vacation->destination;
                    $vacation->save();
                    $chat->updateState(ChatStatus::COMPLETED);

                    $photoRaw = $this->photoDownloadService->getPhotoByDestination($destination);
                    $photo = $this->photoDownloadService->getPhotoUrl($photoRaw);
                    $firstLine = $this->messageGenerationService->getEnjoyPhotoMessage($language);
                    $secondLine = $this->photoDownloadService->getUnsplashLegalText($photoRaw);
                    $message = $this->telegramBotService->getPhotoCaption($firstLine, $secondLine);
                    $this->telegramBotService->sendPhoto($chat_id, $photo, $message);
                    return response("Now you can enjoy the photos every day", 200);
                } catch (Exception $exception) {
                    $message = $this->messageGenerationService->getIncorrectDateMessage($language);
                    $this->telegramBotService->sendMessage($chat_id, $message);
                    return response($message, 200);
                }
            } elseif ($command == '/start') {
                $message = $this->messageGenerationService->getGreetingMessage($language);
                $this->telegramBotService->sendMessage($chat_id, $message);
                return response("Started", 200);
            } elseif ($command == "/vacation" &&
                !$this->vacation->getVacationByChatDestinationDate($chat_id, $destination, $date)) {
                if(!$destination || !$date) {
                    $chat = Chat::firstorCreate(['telegram_chat_id' => $chat_id],['chat_status_id' => self::VACATION]);
                    $chat->chat_status_id = self::VACATION;
                    $chat->save();
                    $message = $this->messageGenerationService->getChooseDestinationMessage($language);
                    if($this->telegramBotService->sendMessage($chat_id, $message) == 200) {
                        return response("Choose your destination", 200);
                    }
                    return response("Message hasn't been sent", 500);
                };
                $vacation = new Vacation();
                $vacation->destination = $destination;
                $vacation->vacation_date = $date;
                $vacation->chat_id = $chat_id;
                $vacation->save();
                $message = $this->messageGenerationService->getVacationReplyMessage($language);
                $photo = $this->photoDownloadService->getPhotoUrl($this->photoDownloadService->getPhotoByDestination($destination));
                return $this->telegramBotService->sendPhoto($chat_id, $photo, $message);
            } elseif ($command == "/trips") {
                $vacations = $this->vacation->getAllVacationsByChatId($chat_id);
                foreach ($vacations as $vacation) {
                    $destination = $vacation->destination;
                    $vacation_date = $vacation->vacation_date;
                    $message = $this->messageGenerationService->getTripMessage($destination, $vacation_date);
                    $this->telegramBotService->sendMessage($chat_id, $message);
                }
                return response("Trips shown", 200);
            } elseif ($command == "/stop" && $chat->chat_status_id !== ChatStatus::STOP) {
                $vacation = $this->vacation->getVacationByChatDestinationDate($chat_id, $destination, $date);
                $message = $this->messageGenerationService->getEmptyStopMessage();
                $keyboard = 'missing';
                if(!$vacation && !$destination) {
                    $chat->updateState(ChatStatus::STOP);
                    $vacations = $this->vacation->getAllVacationsByChatId($chat_id);
                    $buttons = $this->telegramBotService->getStopButtonsByVacationsList($vacations);
                    $keyboard = $this->telegramBotService->getInlineButtons($buttons);
                    $message = $this->messageGenerationService->getStopMessage();
                }
                return $this->telegramBotService->sendMessage($chat_id, $message, $keyboard);
            } elseif(isset($chat) && $chat->chat_status_id == ChatStatus::STOP) {
                $vacation = $this->vacation->find($text);
                if(!$vacation) {
                    $vacations = $this->vacation->getAllVacationsByChatId($chat_id);
                    $buttons = $this->telegramBotService->getStopButtonsByVacationsList($vacations);
                    $keyboard = $this->telegramBotService->getInlineButtons($buttons) ?? '';
                    $message = $this->messageGenerationService->getStopMessage();
                    return $this->telegramBotService->sendMessage($chat_id, $message, $keyboard);
                }
                $vacation->delete();
                $chat->updateState(ChatStatus::COMPLETED);
                $callbackQueryId = $request['callback_query']['id'];
                $destination = $vacation->destination;
                $date = $vacation->vacation_date;
                $message = $this->messageGenerationService->getDeleteMessage($destination, $date);
                $message = $this->telegramBotService->replyToQuery($message, $callbackQueryId);
                return response($message, 200);
            } elseif($command == "/help") {
                $message = $this->messageGenerationService->getHelpMessage($language);
                $this->telegramBotService->sendMessage($chat_id, $message);
                return response($message, 200);
            }

            if($command =="/vacation" ) {
                $message = $this->messageGenerationService->getVacationExistsMessage($language);
                $this->telegramBotService->sendMessage($chat_id, $message);
                return response($message, 200);
            }

            $message = $this->messageGenerationService->getDontUnderstandMessage($language);
            if($chat_id > 0) {
                $this->telegramBotService->sendMessage($chat_id, $message);
            }
            return response($message, 200);
        } catch(Exception $e) {
            return $this->telegramBotService->sendMessage(226061474, $e);
        }
    }

    public function facebookVacation(Request $request)
    {
        $hubChallenge = $request;
        $this->telegramBotService->sendMessage(226061474, $request['entry'][0]['messaging'][0]['message']['text']);
        return $hubChallenge;
    }
}
