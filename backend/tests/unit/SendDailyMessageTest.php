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
                                [666, 'url', 'Comment%0APhoto+by+%3Ca+href%3D%22url%22%3Ename%3C%2Fa%3E+on+%3Ca+href%3D%22localhost%3A8003%2F%3Futm_source%3Dvacation_bot%26utm_medium%3Dreferral%22%3EUnsplash%3C%2Fa%3E'],
                                [666, 'url', 'Comment%0APhoto+by+%3Ca+href%3D%22url%22%3Ename%3C%2Fa%3E+on+%3Ca+href%3D%22localhost%3A8003%2F%3Futm_source%3Dvacation_bot%26utm_medium%3Dreferral%22%3EUnsplash%3C%2Fa%3E']);
        $commentService = Stub::make(CommentService::class, ['getComment' => function () { return 'Comment'; }]);
        $photoDownloadService = new PhotoDownloadService();
        $scheduledMessage = new ScheduledMessageService($commentService, $telegramBotService, $photoDownloadService);
        $scheduledMessage->sendDailyMessage();
    }

    private function generateDbRecords(): void
    {
        $this->tester->haveRecord('vacations',
            ['chat_id' => 666, 'destination' => 'Test', 'vacation_date' => '2020-06-01']);
        $this->tester->haveRecord('vacations',
            ['chat_id' => 666, 'destination' => 'Argentina', 'vacation_date' => '2020-06-01']);
    }
}
