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

    public function testStartCommand()
    {
        $message = "Hi👋\nI will send photo of your vacation destination every day until the flight at 9am!\nJust write /vacation.";
        $teleramBotService = $this->getMockBuilder(TelegramBotService::class)
            ->setMethods(['sendMessage'])
            ->getMock();
        //expects the correct message to injected to sendMessage method on the first vacation
        $teleramBotService->expects(self::once())
            ->method('sendMessage')
            ->with(666, $message);
        $botController = $this->tester->generateBotController($teleramBotService);
        $request = $this->generateRequest();
        $botController->vacation($request);
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
