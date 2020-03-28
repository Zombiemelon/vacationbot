<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Http\Controllers\BotController;
use App\Services\MatchServices\BotMatchService;
use App\Services\MessageGenerationService;
use App\Services\PhotoDownloadService;
use App\Vacation;
use Codeception\Module;
use Codeception\Stub;
use Exception;

class Unit extends Module
{
    public function getFacebookMessage()
    {
        $request = json_decode('[
            {
              "id": "104930794484894",
              "time": 1585081451957,
              "messaging": [
                {
                  "sender": {
                    "id": "3431758906850992"
                  },
                  "recipient": {
                    "id": "104930794484894"
                  },
                  "timestamp": 1585081451544,
                  "message": {
                    "mid": "m_kH362kEC9wFlbIMMdfeaCXIughvl7n8-Fv2d-1NAjbpdobKNpRvKz8uYCzR_YeHdJMtuXZejUi9vBXMwVOBxzQ",
                    "text": "/vacation"
                  }
                }
              ]
            }
          ]');

        return $request;
    }
}
