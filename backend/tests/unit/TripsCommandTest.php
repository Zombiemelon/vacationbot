<?php

use App\Services\MessageGenerationService;
use App\Services\PhotoDownloadService;
use App\Services\TelegramBotService;
use App\Http\Controllers\BotController;
use App\Vacation;
use Codeception\Stub\Expected;
use Illuminate\Http\Request;

class TripsCommandTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;
    protected $destinationOne = "Bali";
    protected $dateOne = "2020-06-01";
    protected $destinationTwo = "New-York";
    protected $dateTwo = "2021-07-02";

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testStopMessage()
    {
        $this->generateDbRecords();
        $sendMessageService = $this->getMockBuilder(TelegramBotService::class)
                                ->setMethods(['sendMessage'])
                                ->getMock();
        //expects that number of messages equals number of vacations
        $sendMessageService->expects(self::exactly(2))
            ->method('sendMessage');
        //expects the correct message to injected to sendMessage method on the first vacation
        $sendMessageService->expects(self::at(0))
                            ->method('sendMessage')
                            ->with(666, "$this->destinationOne $this->dateOne");
        //expects the correct message to injected to sendMessage method on the second vacation
        $sendMessageService->expects(self::at(1))
            ->method('sendMessage')
            ->with(666, "$this->destinationTwo $this->dateTwo");
        $botController = $this->generateBotController($sendMessageService);
        $request = $this->generateRequest();
        $botController->vacation($request);
    }

    private function generateDbRecords() :void
    {
        $this->tester->haveRecord('vacations',
            ['chat_id' => 666, 'destination' => $this->destinationOne, 'vacation_date' => $this->dateOne]);
        $this->tester->haveRecord('vacations',
            ['chat_id' => 666, 'destination' => $this->destinationTwo, 'vacation_date' => $this->dateTwo]);
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
     * @return Request
     */
    private function generateRequest() :Request
    {
        $request = new Request();
        $request['message'] = [
            'text' => "/trips",
            'chat' => [
                'id' => 666
            ],
            'from' => [
                'language_code' => 'en'
            ]
        ];

        return $request;
    }
}
