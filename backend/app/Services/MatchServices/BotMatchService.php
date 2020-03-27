<?php


namespace App\Services\MatchServices;


use App\Interfaces\BotInterface;
use App\Services\FacebookBotService;
use App\Services\TelegramBotService;

class BotMatchService
{
    public function getBot(string $route): BotInterface
    {
        switch ($route) {
            case 'facebook':
                return new FacebookBotService();
                break;
            case 'telegram':
                return new TelegramBotService();
        }
    }

}
