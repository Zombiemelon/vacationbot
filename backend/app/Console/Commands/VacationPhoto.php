<?php

namespace App\Console\Commands;

use App\Services\CommentService;
use App\Services\ScheduledMessageService;
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

    protected $scheduledMessageService;

    /**
     * Create a new command instance.
     * @param ScheduledMessageService $scheduledMessageService
     */
    public function __construct(ScheduledMessageService $scheduledMessageService)
    {
        parent::__construct();
        $this->scheduledMessageService = $scheduledMessageService;
    }

    /**
     * @throws Exception
     */
    public function handle()
    {
        $this->scheduledMessageService->sendDailyMessage();
    }
}
