<?php

use App\Services\CommentService;
use App\Services\ScheduledMessageService;
use App\Services\TelegramBotService;
use App\Services\PhotoDownloadService;
use Codeception\Test\Unit;
use Codeception\Util\Stub;

class SendDailyMessageTest extends Unit
{
    /**
     * @var UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests that several messages are sent when the command is triggered
    public function testSomeFeature()
    {
        $this->generateDbRecords();
        $telegramBotService = $this->getMockBuilder(TelegramBotService::class)
                            ->setMethods(['sendPhoto'])
                            ->getMock();
        $telegramBotService->expects(self::exactly(2))
                            ->method('sendPhoto')
                            ->withConsecutive(
                                [666, 'url', 'Comment
Photo by <a href="url">name</a> on <a href="localhost:8003/?utm_source=vacation_bot&utm_medium=referral">Unsplash</a>'],
                                [666, 'url', 'Comment
Photo by <a href="url">name</a> on <a href="localhost:8003/?utm_source=vacation_bot&utm_medium=referral">Unsplash</a>']);
        $commentService = Stub::make(CommentService::class, ['getComment' => function () { return 'Comment'; }]);
        $photoDownloadService = new PhotoDownloadService();
        $scheduledMessage = new ScheduledMessageService($commentService, $telegramBotService, $photoDownloadService);
        $scheduledMessage->sendDailyMessage();
    }

    private function generateDbRecords(): void
    {
        $this->tester->haveRecord('vacations',
            ['chat_id' => 666, 'destination' => 'Test', 'vacation_date' => '2020-06-01', 'status' => 1]);
        $this->tester->haveRecord('vacations',
            ['chat_id' => 666, 'destination' => 'Argentina', 'vacation_date' => '2020-06-01', 'status' => 1]);
        $this->tester->haveRecord('vacations',
            ['chat_id' => 666, 'destination' => 'Minsk', 'vacation_date' => '2020-06-01', 'status' => 2]);
    }
}
