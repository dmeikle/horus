<?php

namespace Gossamer\Horus\EventListeners;

class Event
{
    private $eventName = null;
    
    private $params = null;
    
    
    public function __construct($eventName, $params) {
        $this->eventName = $eventName;
        $this->params = $params;
    }
    
    public function getEventName() {
        return $this->eventName;
    }
    
    public function getParams() {
        return $this->params;
    }
    
    public function getParam($key) {
        if(array_key_exists($key, $this->params)) {
            return $this->params[$key];
        }
        
        return null;
    }
}
