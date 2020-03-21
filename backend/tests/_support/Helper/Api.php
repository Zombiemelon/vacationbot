<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use Codeception\Module;
use donatj\MockWebServer\MockWebServer;
use Exception;

class Api extends Module
{
    private $server;

    public function setMockWebServer()
    {
        $this->server = new MockWebServer(8002);
        $this->server->start();

        return $this->server;
    }

    public function stopMockWebServer()
    {
        $this->server->stop();
    }
}
