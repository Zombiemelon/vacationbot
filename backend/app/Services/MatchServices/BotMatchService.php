<?php


namespace App\Services\MatchServices;


use App\Interfaces\BotInterface;
use App\Services\FacebookBotService;
use App\Services\TelegramBotService;
use Illuminate\Http\Request;

class BotMatchService
{
    /**
     * @param string $route
     * @return BotInterface
     */
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

    /**
     * @param Request $request
     * @return string
     */
    public function getRouteName(Request $request): string
    {
        return $request->route()->getName();
    }


}
