<?php

use App\Services\TelegramBotService;
use App\Vacation;
use Codeception\Test\Unit;
use Illuminate\Http\Request;

class GetCommandTest extends Unit
{
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @dataProvider dataProvider
     * @param string $command
     * @param string $expectedOutput
     */
    public function testGetCommand(string $command, string $expectedOutput)
    {
        $telegramBotService = new TelegramBotService();
        $request = $this->generateRequest($command);
        //generates Text based on the request
        $text = $telegramBotService->getText($request);
        $actualCommand = $telegramBotService->getCommand($text);
        $this->assertEquals($expectedOutput, $actualCommand);
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

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
                ["command" => "/trips@VacationPhotoBot", "expectedOutput" => "/trips"],
                ["command" => "/trips", "expectedOutput" => "/trips"]
            ];
    }
}
