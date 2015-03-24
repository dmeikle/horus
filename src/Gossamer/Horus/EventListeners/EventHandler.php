<?php


namespace Gossamer\Horus\EventListeners;

use Monolog\Logger;
use Gossamer\Horus\Core\Request;

class EventHandler
{
    private $listeners = array();
    
    private $state = null;
    
    private $params = null;
    
    private $logger = null;
   
    private $request = null;
    
    public function __construct(Logger $logger, Request $request) {
        
        $this->logger = $logger;
        $this->request = $request;
        
    }
    
    
    public function addListener($listener) {
        
        $this->listeners[] = $listener;
        $this->logger->addDebug($listener['listener'] . ' added to listeners');
    }


    public function notifyListeners() {
        $this->logger->addDebug('notifying listeners');
        
        foreach($this->listeners as $listener) {
            $listenerClass= $listener['listener'];
            if(class_exists($listenerClass) && $this->state == $listener['event']) {
                $eventListener = new $listenerClass($this->logger, $this->request);    
                $eventListener->setConfig($listener);
                $eventListener->execute($this->state, $this->params);
            } else {
                $this->logger->addError($listenerClass . ' not found by EventHandler::notifyListeners');
            }
        }
    }

    
    public function setState($state, $params) {
        $this->state = $state;
        $this->params = $params;
        
        $this->notifyListeners();
    }
    
}