<?php

use App\Services\TelegramBotService;
use App\Vacation;
use Codeception\Test\Unit;

class GetButtonsByVacationsListTest extends Unit
{
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testInlineKeyboardGeneration()
    {
        $this->generateDbRecords();
        $vacations = (new Vacation())->getAllVacationsByChatId(666);
        $telegramBotService = new TelegramBotService();
        $expectedButtons = [["text" => "Bali 2020-01-01", "callbackData" => 100],
                        ["text" => "Argentina 2020-01-10", "callbackData" => 200]];
        $realValue = $telegramBotService->getStopButtonsByVacationsList($vacations);
        $this->assertEquals($expectedButtons, $realValue);
    }

    private function generateDbRecords() :void
    {
        $this->tester->haveRecord('vacations',
            ['id' => 100, 'chat_id' => 666, 'destination' => 'Bali', 'vacation_date' => '2020-01-01']);
        $this->tester->haveRecord('vacations',
            ['id' => 200, 'chat_id' => 666, 'destination' => 'Argentina', 'vacation_date' => '2020-01-10']);
    }
}
