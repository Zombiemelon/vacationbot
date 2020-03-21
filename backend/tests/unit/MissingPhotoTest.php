<?php

use App\Services\MessageGenerationService;
use App\Services\PhotoDownloadService;
use App\Services\TelegramBotService;
use App\Http\Controllers\BotController;
use App\Vacation;
use Codeception\Stub\Expected;
use Codeception\Util\Stub;
use Illuminate\Http\Request;

class MissingPhotoTest extends \Codeception\Test\Unit
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


    public function testSomeFeature()
    {
        $this->generateDbRecords();
        $message = 'No such place😢 Try another place. For example, Moscow';
        $sendMessageService = $this->getMockBuilder(TelegramBotService::class)
                            ->setMethods(['sendMessage'])
                            ->getMock();
        //expects the correct message to injected to sendMessage method
        $sendMessageService->expects(self::once())
                            ->method('sendMessage')
                            ->with(666, $message);
        $botController = $this->generateBotController($sendMessageService);
        $request = $this->generateRequest();
        $botController->vacation($request);
    }

    private function generateDbRecords(): void
    {
        $this->tester->haveRecord('chats', ['telegram_chat_id' => 666, 'chat_status_id' => BotController::VACATION]);
    }

    /**
     * @param $sendMessageService
     * @return BotController
     * @throws Exception
     */
    private function generateBotController($sendMessageService) :BotController
    {
        $vacation = new Vacation();
        $photoDownloadService = Stub::make(PhotoDownloadService::class, ['getPhotoByDestination' => function () { throw new Exception("[\"No photos found.\"]");}]);
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
}
