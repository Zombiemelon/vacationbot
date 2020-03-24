<?php

use App\Services\ScheduledMessageService;
use App\Console\Commands\VacationPhoto;
use Codeception\Test\Unit;

class SendDailyMessageCommandTest extends Unit
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

    // tests that command triggers daily message
    public function testSomeFeature()
    {
        $scheduledMessageService = $this->getMockBuilder(ScheduledMessageService::class)
                            ->disableOriginalConstructor()
                            ->setMethods(['sendDailyMessage'])
                            ->getMock();
        $scheduledMessageService->expects(self::once())
                            ->method('sendDailyMessage');
        $vacationPhoto = new VacationPhoto($scheduledMessageService);
        $vacationPhoto->handle();
    }
}
