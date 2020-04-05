<?php

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;

class CreateVacationCest
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

    private function sendVacationMessage(ApiTester $I)
    {
        self::$__mock_webserver->setResponseOfPath(
            "/sendMessage",
            new Response("", [],200)
        );

        $I->sendPOST("/AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ", [
            "message" => [
                "text" => "/vacation",
                "chat" => [
                    "id" => 666
                ],
                "from" => [
                    "language_code" => "en"
                ]
            ]
        ]);
    }

    public function sendDestination(ApiTester $I, $destination)
    {
        self::$__mock_webserver->setResponseOfPath(
            "/sendMessage",
            new Response("", [],200)
        );

        $I->sendPOST("/AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ", [
            "message" => [
                "text" => $destination,
                "chat" => [
                    "id" => 666
                ],
                "from" => [
                    "language_code" => "en"
                ]
            ]
        ]);
    }

    public function sendDate(ApiTester $I, $date)
    {
        self::$__mock_webserver->setResponseOfPath(
            "/sendPhoto",
            new Response("", [],200)
        );

        $I->sendPOST("/AAEAoCQbymlnr_6sDs1rCsjQcRxLtbLtWZQ", [
            "message" => [
                "text" => $date,
                "chat" => [
                    "id" => 666
                ],
                "from" => [
                    "language_code" => "en"
                ]
            ]
        ]);
    }

    // test that vacation is created if it doesn"t exist yet and it has destination & date
    /**
     * @dataProvider dataProvider
     * @param ApiTester $I
     * @param \Codeception\Example $example
     */
    public function testVacationCreation(ApiTester $I, \Codeception\Example $example)
    {
        $this->sendVacationMessage($I);
        //checks that if it is the first /vacation message then a chat with status BotController::VACATION should be created
        $I->seeRecord("chats", ["telegram_chat_id" => 666, "chat_status_id" => \App\Http\Controllers\BotController::VACATION]);
        $I->seeResponseEquals("Choose your destination");

        $destination = "Bali";
        $this->sendDestination($I, $destination);
        //check that if the destination is selected then chat has changed states to BotController::SELECT_DATE
        // and vacation has been created
        $I->seeRecord("chats", ["telegram_chat_id" => 666, "chat_status_id" => \App\Http\Controllers\BotController::SELECT_DATE]);
        $I->seeRecord('vacations', ["chat_id" => 666, 'destination' => $destination]);
        $I->seeResponseEquals("Now select the date");

        $date = $example["date"];
        $status = $example["status"];
        $vacationDate = $example["vacationDate"];
        $response = $example["response"];
        $this->sendDate($I, $date);
        //check that if the date is set
        $I->seeRecord("chats", ["telegram_chat_id" => 666, "chat_status_id" => $status]);
        $I->seeRecord('vacations', ["chat_id" => 666, 'vacation_date' => $vacationDate]);
        $I->seeResponseEquals($response);
    }

    /**
     * @return array
     */
    protected function dataProvider() // alternatively, if you want the function to be public, be sure to prefix it with `_`
    {
        return [
            ['date'=>"Test", 'status'=>\App\Http\Controllers\BotController::SELECT_DATE, 'vacationDate'=>'2020-01-01', 'response'=>"Please select the date in format 2020-04-06!📅"],
            ['date'=>"2020-06-01", 'status'=>\App\Http\Controllers\BotController::COMPLETED, 'vacationDate'=>'2020-06-01', 'response'=>"Now you can enjoy the photos every day"],
        ];
    }
}
