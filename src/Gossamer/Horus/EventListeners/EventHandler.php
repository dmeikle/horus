<?php


namespace Gossamer\Horus\EventListeners;

use Gossamer\Horus\Http\HttpInterface;
use Monolog\Logger;
use Gossamer\Horus\Core\Request;

class EventHandler
{
    private $listeners = array();
    
    private $state = null;
    
    private $params = null;
    
    private $container = null;
    
    private $logger = null;
   
    private $request = null;

    private $response = null;
    
    private $datasourceFactory = null;
    
    private $datasources = null;
    
    private $datasourceKey = null;
    
    private $eventDispatcher = null;

    /**
     * EventHandler constructor.
     * @param Logger $logger
     * @param HttpInterface $request
     * @param HttpInterface $response
     */
    public function __construct(Logger $logger, HttpInterface $request, HttpInterface $response) {
        
        $this->logger = $logger;
        $this->request = $request;
        $this->response = $response;        
    }



    /**
     * accessor
     *
     * @param type $datasourceKey
     */
    public function setDatasourceKey($datasourceKey) {
        $this->datasourceKey = $datasourceKey;
    }

    /**
     * accessor
     *
     * @param DatasourceFactory $factory
     * @param array $datasources
     */
    public function setDatasources(DatasourceFactory $factory, array $datasources) {
        $this->datasourceFactory = $factory;
        $this->datasources = $datasources;
    }

    /**
     * @param EventDispatcher $eventDispatcher
     */
    public function setEventDispatcher(EventDispatcher &$eventDispatcher) {
        $this->eventDispatcher = $eventDispatcher;
    }
    
    
    public function addListener($listener) {
        
        $this->listeners[] = $listener;
        $this->logger->addDebug($listener['listener'] . ' added to listeners');
    }


    /**
     * accessor
     *
     * @param Container $container
     */
    public function setContainer(Container &$container) {
        $this->container = $container;
    }
    


    /**
     * traverses list of listeners and executes their calls
     */
    public function notifyListeners() {
        
        foreach ($this->listeners as $listener) {

            $listenerClass = $listener['listener'];

            $handler = array($listenerClass, 'on_' . $this->state);
            if(!class_exists($listenerClass)) {
                throw new \Exception($listenerClass . ' does not exist');
            }

            if ($this->state == $listener['event'] && is_callable($handler)) {
                unset($listener['listener']);     
                
                $eventListener = new $listenerClass($this->logger, $this->request, $this->response);
                $eventListener->setDatasources($this->datasourceFactory, $this->datasources);
                $eventListener->setDatasourceKey($this->datasourceKey);
                $eventListener->setEventDispatcher($this->eventDispatcher);
                $eventListener->setConfig($listener);               
                $eventListener->execute($this->state, $this->params);
                
            }
        }
    }

    /**
     * @param $state
     * @param $params
     * @throws \Exception
     */
    public function setState($state, $params) {
        $this->state = $state;
        $this->params = $params;
        
        $this->notifyListeners();
    }
    
}