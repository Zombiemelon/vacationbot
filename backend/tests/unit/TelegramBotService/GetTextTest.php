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

    //tests the full text message
    public function testGetFullText()
    {
        $telegramBotService = new TelegramBotService();
        $request = $this->generateRequest();
        $text = $telegramBotService->getText($request);
        $this->assertEquals("Test text", $text);
    }

    //tests the empty text message
    public function testGetEmptyText()
    {
        $telegramBotService = new TelegramBotService();
        $request = $this->generateRequestEmptyText();
        $text = $telegramBotService->getText($request);
        $this->assertEquals("", $text);
    }

    public function testFacebookText()
    {
//        $telegramBotService = new TelegramBotService();
//        $request = $this->generateFacebookRequest();
//        $text = $telegramBotService->getText($request);
//        $this->assertEquals("", $text);
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

    /**
     * @return Request
     */
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

    private function generateFacebookRequest(): Request
    {
        $request = new Request();
        $request = json_decode('[
            {
              "id": "104930794484894",
              "time": 1585081451957,
              "messaging": [
                {
                  "sender": {
                    "id": "3431758906850992"
                  },
                  "recipient": {
                    "id": "104930794484894"
                  },
                  "timestamp": 1585081451544,
                  "message": {
                    "mid": "m_kH362kEC9wFlbIMMdfeaCXIughvl7n8-Fv2d-1NAjbpdobKNpRvKz8uYCzR_YeHdJMtuXZejUi9vBXMwVOBxzQ",
                    "text": "k"
                  }
                }
              ]
            }
          ]');

        $request['entry'] = $request;

        return $request;
    }
}
