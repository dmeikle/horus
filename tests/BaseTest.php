<?php

namespace tests;

use Gossamer\Horus\Http\Request;
use Gossamer\Horus\Http\Response;
use Gossamer\Neith\Logging\MonologLogger;
use Gossamer\Set\Utils\Container;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

class BaseTest extends \PHPUnit_Framework_TestCase
{
    const GET = 'GET';

    const POST = 'POST';

    protected function getLogger() {

        $logger = new MonologLogger('phpUnitTest');
        $logger->pushHandler(new StreamHandler(__SITE_PATH . "/logs/phpunit.log", Logger::DEBUG));


        return $logger;
    }

    public function setRequestMethod($method) {
        define("__REQUEST_METHOD", $method);
    }

    public function setURI($uri) {
        define('__URI', $uri);
        define("__REQUEST_URI", $uri . '/');
    }

    public function testBase() {

    }

    protected function getResponse() {
        $response = new Response();

        return $response;
    }


    protected function getRequest() {
        $request = new Request();

        return $request;
    }

    protected function getDatasources() {
        return array(
            'default' => array(
                'class' => 'Gossamer\Pesedget\Database\DBConnection',
                'credentials' => array(
                    'host' => 'localhost',
                    'username' => 'bh_user',
                    'password' => 'sup3rfr3ak',
                    'dbName' => 'BHDB5_master'
                )
            )
        );
    }

    protected function getContainer() {
        return new Container();
    }
}
