<?php


namespace App\Interfaces;


use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Integer;

interface BotInterface
{
    public function getText(Request $request): string;

    public function getCommand(string $text): string;

    public function getChatId(Request $request): string;

    public function sendMessage(string $chat_id, string $message = '', string $keyboard = 'missing'): int;

    public function getPhotoCaption(string $firstLine, string $secondLine): string;
}
