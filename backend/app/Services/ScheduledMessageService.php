<?php


namespace App\Services;

use App\Vacation;
use Exception;

class ScheduledMessageService
{
    private $commentService;
    private $telegramBotService;
    private $photoDownloadService;

    public function __construct(CommentService $commentService, TelegramBotService $telegramBotService, PhotoDownloadService $photoDownloadService)
    {
        $this->commentService = $commentService;
        $this->telegramBotService = $telegramBotService;
        $this->photoDownloadService = $photoDownloadService;
    }

    public function sendDailyMessage()
    {
        $vacation = new Vacation();
        $vacations = $vacation->getActiveVacations();
        foreach ($vacations as $vacation) {
            if($vacation->vacation_date < date('Y-m-d H:i:s')) {
                $vacation->setInactive();
            }
            $destination = $vacation->destination;
            $photoDownloadService = $this->photoDownloadService;
            try {
                $photoRaw = $photoDownloadService->getPhotoByDestination($destination);
            } catch (Exception $exception) {
                continue;
            }
            $photo = $photoDownloadService->getPhotoUrl($photoRaw);
            $caption = $this->commentService->getComment($vacation->destination, $vacation->vacation_date);
            $caption .= $photoDownloadService->getUnsplashLegalText($photoRaw);
            $chat_id = $vacation->chat_id;
            try {
                $this->telegramBotService->sendPhoto($chat_id, $photo, $caption);
            } catch(Exception $exception) {
                if($exception->getCode() == 403) {
                    $vacation->setInactive();
                }
            }
        }
    }
}
