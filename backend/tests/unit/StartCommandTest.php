<?php

use App\Http\Controllers\BotController;
use App\Services\MessageGenerationService;
use App\Services\PhotoDownloadService;
use App\Services\TelegramBotService;
use App\Vacation;
use Codeception\Test\Unit;
use Illuminate\Http\Request;

class StartCommandTest extends Unit
{

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testStartCommand()
    {
        $message = urlencode("Hi👋\nI will send photo of your vacation destination every day until the flight at 9am!\nJust write /vacation.");
        $sendMessageService = $this->getMockBuilder(TelegramBotService::class)
            ->setMethods(['sendMessage'])
            ->getMock();
        //expects the correct message to injected to sendMessage method on the first vacation
        $sendMessageService->expects(self::once())
            ->method('sendMessage')
            ->with(666, $message);
        $botController = $this->generateBotController($sendMessageService);
        $request = $this->generateRequest();
        $botController->vacation($request);
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
            'text' => "/start",
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
