<?php

use donatj\MockWebServer\MockWebServer;
use donatj\MockWebServer\Response;

class CreateFacebookVacationCest
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

    /**
     * @param ApiTester $I
     */
    public function sendDestination(ApiTester $I)
    {
        self::$__mock_webserver->setResponseOfPath(
            "/sendMessage",
            new Response("", [],200)
        );

        $request = [
            "object" => "page",
            "entry" => [
                [
                    "id" => "104930794484894",
                    "time" => 1585081451957,
                    "messaging" => [
                        [
                            "sender" => [
                                "id" => "3431758906850992"
                            ],
                            "recipient" => [
                                "id" => "104930794484894"
                            ],
                            "timestamp" =>1585081451544,
                            "message" => [
                                "mid" => "m_kH362kEC9wFlbIMMdfeaCXIughvl7n8-Fv2d-1NAjbpdobKNpRvKz8uYCzR_YeHdJMtuXZejUi9vBXMwVOBxzQ",
                                "text" => "Bali"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $I->sendPOST("/facebookWebhook", $request);
    }

    /**
     * @param ApiTester $I
     * @param $date
     */
    public function sendDate(ApiTester $I, $date)
    {
        self::$__mock_webserver->setResponseOfPath(
            "/sendPhoto",
            new Response("", [],200)
        );

        $request = [
            "object" => "page",
            "entry" => [
                [
                    "id" => "104930794484894",
                    "time" => 1585081451957,
                    "messaging" => [
                        [
                            "sender" => [
                                "id" => "3431758906850992"
                            ],
                            "recipient" => [
                                "id" => "104930794484894"
                            ],
                            "timestamp" =>1585081451544,
                            "message" => [
                                "mid" => "m_kH362kEC9wFlbIMMdfeaCXIughvl7n8-Fv2d-1NAjbpdobKNpRvKz8uYCzR_YeHdJMtuXZejUi9vBXMwVOBxzQ",
                                "text" => $date
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $I->sendPOST("/facebookWebhook", $request);
    }

    private function sendFacebookVacationMessage(ApiTester $I)
    {
        self::$__mock_webserver->setResponseOfPath(
            "/sendMessage",
            new Response("", [],200)
        );

        $request = [
            "object" => "page",
            "entry" => [
                [
                    "id" => "104930794484894",
                    "time" => 1585081451957,
                    "messaging" => [
                        [
                            "sender" => [
                                "id" => "3431758906850992"
                            ],
                            "recipient" => [
                                "id" => "104930794484894"
                            ],
                            "timestamp" =>1585081451544,
                            "message" => [
                                "mid" => "m_kH362kEC9wFlbIMMdfeaCXIughvl7n8-Fv2d-1NAjbpdobKNpRvKz8uYCzR_YeHdJMtuXZejUi9vBXMwVOBxzQ",
                                "text" => "/vacation"
                            ]
                        ]
                    ]
                ]
            ]
        ];

        $I->sendPOST("/facebookWebhook", $request);
    }

    /**
     * @dataProvider dataProvider
     * @param ApiTester $I
     * @param \Codeception\Example $example
     */
    public function testFacebookVacationCreation(ApiTester $I, \Codeception\Example $example)
    {
        $this->sendFacebookVacationMessage($I);
        //checks that if it is the first /vacation message then a chat with status BotController::VACATION should be created
        $I->seeRecord("chats", ["telegram_chat_id" => 3431758906850992, "chat_status_id" => \App\Http\Controllers\BotController::VACATION]);
        $I->seeResponseEquals("Choose your destination");

        $this->sendDestination($I);
        //check that if the destination is selected then chat has changed states to BotController::SELECT_DATE
        // and vacation has been created
        $I->seeRecord("chats", ["telegram_chat_id" => 3431758906850992, "chat_status_id" => \App\Http\Controllers\BotController::SELECT_DATE]);
        $I->seeRecord('vacations', ["chat_id" => 3431758906850992, 'destination' => "Bali"]);
        $I->seeResponseEquals("Now select the date");

        $date = $example["date"];
        $status = $example["status"];
        $vacationDate = $example["vacationDate"];
        $response = $example["response"];
        $this->sendDate($I, $date);
        //check that if the date is set
        $I->seeRecord("chats", ["telegram_chat_id" => 3431758906850992, "chat_status_id" => $status]);
        $I->seeRecord('vacations', ["chat_id" => 3431758906850992, 'vacation_date' => $vacationDate]);
        $I->seeResponseEquals($response);
    }

    /**
     * @return array
     */
    protected function dataProvider() // alternatively, if you want the function to be public, be sure to prefix it with `_`
    {
        return [
            ['date'=>"2020-06-01", 'status'=>\App\Http\Controllers\BotController::COMPLETED, 'vacationDate'=>'2020-06-01', 'response'=>"Now you can enjoy the photos every day"],
            ['date'=>"Test", 'status'=>\App\Http\Controllers\BotController::SELECT_DATE, 'vacationDate'=>'2020-01-01', 'response'=>"Please select the date in format 2020-04-06!📅"]
        ];
    }
}
