<?php

namespace Gossamer\Horus\EventListeners;

use Monolog\Logger;
use Gossamer\Horus\Core\Request;
use Gossamer\Horus\EventListeners\Event;


class AbstractListener
{
    protected $logger = null;
    
    protected $request = null;
    
    protected $listenerConfig = null;
    
    public function __construct(Logger &$logger, Request &$request = null) {
        $this->logger = $logger;
        $this->request = $request;
    }
    
    public function execute($state, $params) {
        $method = 'on_' . $state;
        $event = null;
        if(!$params instanceof Event) {
            $event = new Event($state, $params);
        } else {
            $event = $params;
        }
       // $this->logger->addDebug('checking listener for method: ' . $method);
        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), array($event));      
            
        }
    }
    
    public function setConfig(array $config) {
        $this->listenerConfig = $config;
    }
}