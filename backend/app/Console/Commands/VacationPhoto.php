<?php

namespace App\Console\Commands;

use App\Services\CommentService;
use App\Services\PhotoDownloadService;
use App\Services\TelegramBotService;
use App\Vacation;
use DateTime;
use Exception;
use Illuminate\Console\Command;

class VacationPhoto extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'photo:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send photo to Telegram';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $vacations = Vacation::all();
        foreach ($vacations as $vacation) {
            if($vacation->vacation_date < date('Y-m-d H:i:s')) {
                $vacation->delete();
            }
            $captionService = new CommentService();
            $caption = $captionService->getComment($vacation->destination, $vacation->vacation_date);
            $destination = $vacation->destination;
            $photoDownloadService = new PhotoDownloadService();
            try {
                $photoRaw = $photoDownloadService->getPhotoByDestination($destination);
            } catch (Exception $exception) {
                continue;
            }
            $photo = $photoDownloadService->getPhotoUrl($photoRaw);
            $caption .= urlencode($photoDownloadService->getUnsplashLegalText($photoRaw));
            $chat_id = $vacation->chat_id;
            $sendPhotoService = new TelegramBotService();
            $sendPhotoService->sendPhoto($chat_id, $photo, $caption);
        }
    }
}
