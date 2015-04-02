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
    
    public function execute($state, Event &$event) {
        $method = 'on_' . $state;
       
       // $this->logger->addDebug('checking listener for method: ' . $method);
        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), array(&$event));
            
        }
    }
    
    public function setConfig(array $config) {
        $this->listenerConfig = $config;
    }
}
