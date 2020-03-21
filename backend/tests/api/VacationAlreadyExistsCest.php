<?php

use App\Http\Controllers\BotController;
use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;

class VacationAlreadyExistsCest
{
    private static $__mock_webserver;

    public function _before(ApiTester $I)
    {
        self::$__mock_webserver = $I->setMockWebServer();
    }

    public function _after(ApiTester $I)
    {
        $I->stopMockWebServer();
    }

    // tests that if vacation already exists the appropriate message is sent
    public function tryToTest(ApiTester $I)
    {
        self::$__mock_webserver->setResponseOfPath(
            '/sendPhoto',
            new Response('yo', [],200)
        );

        $I->haveRecord('vacations', ['chat_id' => 666, 'destination' => 'Bali', 'vacation_date' => '2020-06-01']);
        $I->sendPOST('/AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ', [
            'message' => [
                'text' => "/vacation Bali 2020-06-01",
                'chat' => [
                    'id' => 666
                ],
                'from' => [
                    'language_code' => 'en'
                ]
            ]
        ]);
        $I->seeResponseEquals("Vacation already exists 👯‍♂️");
    }
}
