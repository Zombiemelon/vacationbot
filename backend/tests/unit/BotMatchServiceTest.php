<?php
use App\Services\MatchServices\BotMatchService;
use App\Services\FacebookBotService;
use App\Services\TelegramBotService;

class BotMatchServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @dataProvider dataProvider
     * @param $route
     * @param $expectedBot
     */
    // tests that correct bot class is selected
    public function testBotMatchFacebook($route, $expectedBot)
    {
        $botMatchService = new BotMatchService();
        $bot = $botMatchService->getBot($route);
        $this->assertEquals($expectedBot, $bot);
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            ["route" => "facebook", "expectedBot" => new FacebookBotService()],
            ["route" => "telegram", "expectedBot" => new TelegramBotService()]
        ];
    }
}
