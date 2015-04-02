<?php


namespace Gossamer\Horus\EventListeners;

use Monolog\Logger;
use Gossamer\Horus\Core\Request;

class EventHandler
{
    private $listeners = array();
    
    private $state = null;
    
    private $event = null;
    
    private $logger = null;
   
    private $request = null;
    
    public function __construct(Logger $logger, Request &$request) {
        
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
            $handler = array($listenerClass, 'on_' . $this->state);
        
            if($this->state == $listener['event'] && is_callable($handler)) {
              
                $eventListener = new $listenerClass($this->logger, $this->request);    
                $eventListener->setConfig($listener);
                $eventListener->execute($this->state, $this->event);
            } else {
                $this->logger->addError($listenerClass . ' not found by EventHandler::notifyListeners');             
            }
        }
      
     
    }

    
    public function setState($state, Event &$event) {
        $this->state = $state;
        $this->event = $event;
        
        $this->notifyListeners();
    }
    
}
