<?php


namespace Gossamer\Horus\EventListeners;

use Gossamer\Horus\EventListeners\EventHandler;
use Monolog\Logger;
use Gossamer\Horus\Core\Request;


class EventDispatcher{
    
    private $listeners = array();
 
    private $logger = null;
    
    private $request = null;
    
    private $container = null;
    
    public function __construct($config = array(), Logger $logger, Request &$request) {
       
        $this->logger = $logger;
        $this->request = $request;
      
        if(count($config) > 0) {
            $this->configListeners($config);
        }
    }
    
    public function setContainer(ContainerInterface $container) {
        $this->container = $container;
    }
    
    public function configListeners(array $listeners) {

        foreach($listeners as $uri => $listener) {
            
            if(array_key_exists('listeners', $listener)  && count($listener['listeners']) > 0) {
           
                try{
                    $this->addEventHandler( $uri, $listener['listeners']);   
                }catch(\Exception $e) {
                    //assume the developer has an empty element such as:
                    //listeners:
                    //with no sub elements, which is allowable                                     
                }                
            }
        }
       
    }
    
    private function addEventHandler($uri, array $listeners) {
        foreach($listeners as $listener) {
             
            $handler = new EventHandler($this->logger, $this->request); 
          
            $handler->addListener($listener); 
       
            $this->listen($uri, $handler);
        } 
           
    }
    
    public function listen($uri, EventHandler $handler) {
       
        $this->listeners[$uri][get_class($handler)] = $handler;
    }
 
    public function dispatch($uri, $state, Event &$event) {
     
        if(!array_key_exists($uri, $this->listeners)) {
                
            return;
        }        
     
      
        foreach ($this->listeners[$uri] as $listener)
        {
            $listener->setState($state, $event);
        }
    }
    
    public function getListenerURIs() {
        return array_keys($this->listeners);
    }
}
