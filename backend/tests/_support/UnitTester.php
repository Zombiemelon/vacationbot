<?php

use App\Http\Controllers\BotController;
use App\Services\MatchServices\BotMatchService;
use App\Services\MessageGenerationService;
use App\Services\PhotoDownloadService;
use App\Vacation;
use Codeception\Actor;
use Codeception\Stub;


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class UnitTester extends Actor
{
    use _generated\UnitTesterActions;

    public function generateBotController($telegramBotService) :BotController
    {
        $vacation = new Vacation();
        $photoDownloadService = new PhotoDownloadService();
        $messageGenerationService = new MessageGenerationService ();
        $botMatchService = Stub::make(new BotMatchService, ['getRouteName' => 'telegram', 'getBot' => $telegramBotService]);
        $botController = new BotController($vacation, $photoDownloadService, $messageGenerationService, $botMatchService);
        return $botController;
    }
}
