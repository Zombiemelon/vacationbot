<?php

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;

class ChooseVacationDestinationCest
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

    // tests that if vacation destination or date is missing then choose your destination message should be sent
    public function tryToTest(ApiTester $I)
    {
        self::$__mock_webserver->setResponseOfPath(
            '/sendPhoto',
            new Response('Choose your destination', [],200)
        );

        $I->sendPOST('/AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ', [
            'message' => [
                'text' => "/vacation",
                'chat' => [
                    'id' => 666
                ],
                'from' => [
                    'language_code' => 'en'
                ]
            ]
        ]);
        $I->seeResponseContains("Choose your destination");
    }
}
