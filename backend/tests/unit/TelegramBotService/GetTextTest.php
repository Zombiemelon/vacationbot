<?php

use App\Services\TelegramBotService;
use Codeception\Test\Unit;
use Illuminate\Http\Request;

class GetTextTest extends Unit
{
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    //Tests the full text message
    public function testGetFullText()
    {
        $telegramBotService = new TelegramBotService();
        $request = $this->generateRequest();
        $text = $telegramBotService->getText($request);
        $this->assertEquals("Test text", $text);
    }

    //Tests the empty text message
    public function testGetEmptyText()
    {
        $telegramBotService = new TelegramBotService();
        $request = $this->generateRequestEmptyText();
        $text = $telegramBotService->getText($request);
        $this->assertEquals("", $text);
    }

    /**
     * @return Request
     */
    private function generateRequest() :Request
    {
        $request = new Request();
        $request["edited_message"] = [
                "message_id" => 4200,
                "from" => [
                    "id" => 502374110,
                    "is_bot" => false,
                    "first_name" => "Test",
                    "last_name" => "Test",
                    "username" => "Testtest",
                    "language_code" => "ru"
                ],
                "chat" => [
                    "id" => -311524412,
                    "title" => "Триумфальная мамка",
                    "type" => "group",
                    "all_members_are_administrators" => true
                ],
                "date" => 1584516119,
                "edit_date" => 1584516131,
                "text" => "Test text"
        ];

        return $request;
    }

    private function generateRequestEmptyText() :Request
    {
        $request = new Request();
        $request["edited_message"] = [
            "message_id" => 4200,
            "from" => [
                "id" => 502374110,
                "is_bot" => false,
                "first_name" => "Test",
                "last_name" => "Test",
                "username" => "Testtest",
                "language_code" => "ru"
            ],
            "chat" => [
                "id" => -311524412,
                "title" => "Триумфальная мамка",
                "type" => "group",
                "all_members_are_administrators" => true
            ],
            "date" => 1584516119,
            "edit_date" => 1584516131,
        ];

        return $request;
    }
}
