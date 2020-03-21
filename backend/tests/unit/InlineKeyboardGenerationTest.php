<?php

use App\Services\TelegramBotService;
use Codeception\Test\Unit;

class InlineKeyboardGenerationTest extends Unit
{

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testInlineKeyboardGeneration()
    {
        $telegramBotService = new TelegramBotService();
        $buttonsText = [["text" => "/vacation", "callbackData" => "Yo!"],
                        ["text" => "/vacation", "callbackData" => "No"],
                        ["text" => "/vacation", "callbackData" => "Klop"]];
        $expectedValue = "{\"inline_keyboard\":[[{\"text\":\"\/vacation\",\"callback_data\":\"Yo!\"},{\"text\":\"\/vacation\",\"callback_data\":\"No\"},{\"text\":\"\/vacation\",\"callback_data\":\"Klop\"}]]}";
        $realValue = $telegramBotService->getInlineButtons($buttonsText);
        $this->assertEquals($expectedValue, $realValue);
    }


}
