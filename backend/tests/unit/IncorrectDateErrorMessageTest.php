<?php

use App\ChatStatus;
use App\Services\MessageGenerationService;
use App\Services\PhotoDownloadService;
use App\Services\TelegramBotService;
use App\Http\Controllers\BotController;
use App\Vacation;
use Illuminate\Http\Request;

class IncorrectDateErrorMessageTest extends \Codeception\Test\Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    //tests that:
    //1. If status is Completed then the following message is triggered: "Sorry, I don't understand you🤷‍♂️ Try /vacation to choose your destination."
    //2. If status is Select date then the following message is triggered: "Please select the date in format 2020-04-06!📅"
    /**
     * @dataProvider dataProvider
     * @param $status
     * @param $message
     */
    public function testSomeFeature($status, $message)
    {
        $this->generateDbRecords($status);
        $sendMessageService = $this->getMockBuilder(TelegramBotService::class)
                            ->setMethods(['sendMessage'])
                            ->getMock();
        //expects the correct message to injected to sendMessage method
        $sendMessageService->expects(self::once())
                            ->method('sendMessage')
                            ->with(666, $message);
        $botController = $this->tester->generateBotController($sendMessageService);
        $request = $this->generateRequest();
        $botController->vacation($request);
    }

    /**
     * @param $status
     */
    private function generateDbRecords($status) :void
    {
        $this->tester->haveRecord('chats', ['telegram_chat_id' => 666, 'chat_status_id' => $status]);
        $this->tester->haveRecord('vacations', ['chat_id' => 666, 'destination' => 'Bali', 'vacation_date' => '2020-06-01']);
    }

    /**
     * @return Request
     */
    private function generateRequest() :Request
    {
        $request = new Request();
        $request['message'] = [
            'text' => "test",
            'chat' => [
                'id' => 666
            ],
            'from' => [
                'language_code' => 'en'
            ]
        ];

        return $request;
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            ['status' => ChatStatus::COMPLETED, "message" => "I don't understand you🤷‍♂️ Try /vacation to choose your destination."],
            ['status' => ChatStatus::SELECT_DATE, 'message' => "Please select the date in format 2020-04-06!📅"]
        ];
    }
}
