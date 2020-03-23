<?php

namespace App\Console\Commands;

use App\Services\CommentService;
use App\Services\PhotoDownloadService;
use App\Services\ScheduledMessageService;
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
        $scheduledMessageService = new ScheduledMessageService();
        $scheduledMessageService->sendDailyMessage();
    }
}
