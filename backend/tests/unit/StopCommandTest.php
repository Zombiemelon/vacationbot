<?php

use App\Services\MessageGenerationService;
use App\Services\PhotoDownloadService;
use App\Services\TelegramBotService;
use App\Http\Controllers\BotController;
use App\Vacation;
use Illuminate\Http\Request;
use App\ChatStatus;

class StopCommandTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    protected $destinationOne = "Bali";
    protected $dateOne = "2020-06-01";
    protected $destinationTwo = "New York";
    protected $dateTwo = "2020-10-10";
    protected $fullText = "/stop Bali 2020-06-01";
    protected $incorrectText = "/stop IncorrectDestination";

    protected function _before()
    {

    }

    protected function _after()
    {
    }

    //test that vacation is deleted and message is sent to the user
    /**
     * @dataProvider dataProvider
     * @param $command
     * @param $message
     * @param $destination
     * @param $date
     */
    public function testStopMessage($command, $message, $destination, $date)
    {
        $this->generateDbRecords();
        $telegramBotService = $this->getMockBuilder(TelegramBotService::class)
                                    ->setMethods(['replyToQuery'])
                                    ->getMock();
        //expects the correct message to injected to sendMessage method
        $telegramBotService->expects(self::once())
                            ->method('replyToQuery')
                            ->with($message, 111);
        $botController = $this->generateBotController($telegramBotService);
        $request = $this->generateQueryRequest($command);
        $botController->vacation($request);
        $this->tester->seeRecord('chats',
            ["telegram_chat_id" => 666, "chat_status_id" => ChatStatus::COMPLETED]);
        $this->tester->dontSeeRecord('vacations', ['id' => $command, 'chat_id' => 666, 'destination' => $destination, 'vacation_date' => $date]);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
                ["command" => 100,
                    "message" => "$this->destinationOne $this->dateOne deleted",
                    "destination" => $this->destinationOne,
                    "date" => $this->dateOne],
                ["command" => 200,
                    "message" => "$this->destinationTwo $this->dateTwo deleted",
                    "destination" => $this->destinationTwo,
                    "date" => $this->dateTwo]
               ];
    }

    /**
     * @dataProvider emptyDataProvider
     * @param string $id
     * @param string $command
     * @param string $message
     */
    public function testEmptyStopMessage(string $id, string $command, string $message)
    {
        $this->generateDbRecordsEmpty();
        $keyboard = "{\"inline_keyboard\":[[{\"text\":\"$this->destinationOne $this->dateOne\",\"callback_data\":\"100\"},{\"text\":\"$this->destinationTwo 2020-10-10\",\"callback_data\":\"200\"}]]}";
        ;
        //create a mock where only sendMessage is mocked, the others are untouched
        $telegramBotService = $this->getMockBuilder(TelegramBotService::class)
                                ->setMethods(['sendMessage'])
                                ->getMock();
        $telegramBotService->expects(self::once())
                            ->method('sendMessage')
                            ->with(666, $message, $keyboard);
        $botController = $this->generateBotController($telegramBotService);
        $request = $this->generateRequest($command);
        $botController->vacation($request);
        $this->tester->canSeeRecord("chats", ["telegram_chat_id" => 666, "chat_status_id" => ChatStatus::STOP]);
    }

    /**
     * @dataProvider emptyDataProvider
     * @param string $id
     * @param string $command
     * @param string $message
     */
    public function testEmptyStopMessageWithStopState(string $id, string $command, string $message)
    {
        $this->generateDbRecords();
        $keyboard = "{\"inline_keyboard\":[[{\"text\":\"Bali 2020-06-01\",\"callback_data\":\"100\"},{\"text\":\"$this->destinationTwo 2020-10-10\",\"callback_data\":\"200\"}]]}";
        ;
        //create a mock where only sendMessage is mocked, the others are untouched
        $telegramBotService = $this->getMockBuilder(TelegramBotService::class)
            ->setMethods(['sendMessage'])
            ->getMock();
        $telegramBotService->expects(self::once())
            ->method('sendMessage')
            ->with(666, $message, $keyboard);
        $botController = $this->generateBotController($telegramBotService);
        $request = $this->generateRequest($command);
        $botController->vacation($request);
        $this->tester->canSeeRecord("chats", ["telegram_chat_id" => 666, "chat_status_id" => ChatStatus::STOP]);
    }

    /**
     * @return array
     */
    public function emptyDataProvider()
    {
        return [
            ["id" => 200, "command" => "/stop", "message" => "Choose destination to stop notifications 🙅‍♂️"],
            ["id" => 200, "command" => "/stop@VacationPhotoBot", "message" => "Choose destination to stop notifications 🙅‍♂️"]
        ];
    }

    private function generateDbRecordsEmpty() :void
    {
        $this->tester->haveRecord('vacations',
            ["id" => 100, 'chat_id' => 666, 'destination' => $this->destinationOne, 'vacation_date' => $this->dateOne]);
        $this->tester->haveRecord('vacations',
            ["id" => 200, 'chat_id' => 666, 'destination' => $this->destinationTwo, 'vacation_date' => $this->dateTwo]);
        $this->tester->haveRecord('chats',
            ["telegram_chat_id" => 666, "chat_status_id" => ChatStatus::COMPLETED]);
    }

    private function generateDbRecords() :void
    {
        $this->tester->haveRecord('vacations',
            ['id' => 100, 'chat_id' => 666, 'destination' => $this->destinationOne, 'vacation_date' => $this->dateOne]);
        $this->tester->haveRecord('vacations',
            ['id' => 200, 'chat_id' => 666, 'destination' => $this->destinationTwo, 'vacation_date' => $this->dateTwo]);
        $this->tester->haveRecord('chats',
            ['id' => 300, "telegram_chat_id" => 666, "chat_status_id" => ChatStatus::STOP]);
    }

    /**
     * @param $sendMessageService
     * @return BotController
     */
    private function generateBotController($sendMessageService) :BotController
    {
        $vacation = new Vacation();
        $photoDownloadService = new PhotoDownloadService();
        $messageGenerationService = new MessageGenerationService ();
        $botController = new BotController($sendMessageService, $vacation, $photoDownloadService, $messageGenerationService);
        return $botController;
    }

    /**
     * @param string $text
     * @return Request
     */
    private function generateRequest(string $text) :Request
    {
        $request = new Request();
        $request['message'] = [
            'text' => $text,
            'chat' => [
                'id' => 666
            ],
            'from' => [
                'language_code' => 'en'
            ]
        ];

        return $request;
    }

    private function generateQueryRequest(string $text) :Request
    {
        $request = new Request();
        $request['callback_query'] = [
            'id' => 111,
            'data' => $text,
            'message' => [
                'chat' => [
                    'id' => 666,
                ],
            ],
            'from' => [
                'language_code' => 'en'
            ]
        ];

        return $request;
    }

    //Telegram query JSON
    /*
     * {
  "id": "970926639356725489",
  "from": {
    "id": 226061474,
    "is_bot": false,
    "first_name": "Svetoslav",
    "last_name": "Dimitrov (vacation 24.02 - 28.02)",
    "username": "sdimitrov",
    "language_code": "en"
  },
  "message": {
    "message_id": 1898,
    "from": {
      "id": 1036050185,
      "is_bot": true,
      "first_name": "Vacation Bot 🏝",
      "username": "VacationPhotoBot"
    },
    "chat": {
      "id": 226061474,
      "first_name": "Svetoslav",
      "last_name": "Dimitrov (vacation 24.02 - 28.02)",
      "username": "sdimitrov",
      "type": "private"
    },
    "date": 1583008249,
    "text": "Choose destination to stop notifications 🙅‍♂️",
    "reply_markup": {
      "inline_keyboard": [
        [
          {
            "text": "Bali 2020-04-17",
            "callback_data": "/stop Bali 2020-04-17"
          },
          {
            "text": "/stop 2020-05-09",
            "callback_data": "/stop /stop 2020-05-09"
          },
          {
            "text": "/stop 2020-01-01",
            "callback_data": "/stop /stop 2020-01-01"
          }
        ]
      ]
    }
  },
  "chat_instance": "2031721940576297736",
  "data": "/stop /stop 2020-01-01"
}
     */
}
