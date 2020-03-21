<?php

use donatj\MockWebServer\Response;

class UnknownCommandCest
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

//    // tests that unknown command is triggered
//    public function testUnknownCommandTrigger(ApiTester $I)
//    {
//        self::$__mock_webserver->setResponseOfPath(
//            '/sendPhoto',
//            new Response('test response', [],200)
//        );
//
//        $I->sendPOST('/AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ', [
//            'message' => [
//                'text' => "test",
//                'chat' => [
//                    'id' => 666
//                ],
//                'from' => [
//                    'language_code' => 'en'
//                ]
//            ]
//        ]);
//        $I->seeResponseContains('Unknown command200');
//    }

    //test that message is not sent if status of the chat is completed
    /**
     * @dataProvider dataProvider
     * @param ApiTester $I
     * @param \Codeception\Example $example
     */
    public function testUnknownCommandNotTriggeredIfStatusIsCompleted(ApiTester $I, \Codeception\Example $example)
    {
        self::$__mock_webserver->setResponseOfPath(
            '/sendPhoto',
            new Response('test response', [],200)
        );

        $I->haveRecord('chats', ['telegram_chat_id' => 666, 'chat_status_id' => $example["status"]]);
        $I->sendPOST('/AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ', [
            'message' => [
                'text' => "test",
                'chat' => [
                    'id' => 666
                ],
                'from' => [
                    'language_code' => 'en'
                ]
            ]
        ]);
        $I->seeResponseContains($example["message"]);
    }

    /**
     * @return array
     */
    protected function dataProvider()
    {
        return [
            ['status' => \App\Http\Controllers\BotController::COMPLETED, "message" => "I don't understand you🤷‍♂️ Try /vacation to choose your destination."],
            ['status' => \App\ChatStatus::SELECT_DATE, 'message' => "Please select the date in format 2020-04-06!📅"]
        ];
    }
}
