<?php

/*
 *  This file is part of the Quantum Unit Solutions development package.
 * 
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 * 
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

namespace tests\Gossamer\Horus\EventListeners;

use Gossamer\Horus\EventListeners\EventDispatcher;
use Gossamer\Horus\Core\Request;

/**
 * EventDispatcherTest
 *
 * @author Dave Meikle
 */
class EventDispatcherTest extends \tests\BaseTest {
    
    public function testAddListener() {
        $request = $this->getRequest();
        
        $dispatcher = new EventDispatcher(null, $this->getLogger(), $request);
        $dispatcher->configListeners($this->getListenerConfig());
        $dispatcher->dispatch('all', 'request_start');
              
        $this->assertNotNull($request->getAttribute('result'));
        $this->assertEquals($request->getAttribute('result'), 'TestListener loaded successfully');
    }
    
    private function getListenerConfig() {
        return array( 
            'all' => array(
                'listeners' => array (
                    array(
                        'event' => 'request_start',
                        'listener' => 'tests\\Gossamer\\Horus\\EventListeners\\TestListener' 
                    ),
                    array(
                        'event' => 'request_end',
                        'listener' => 'tests\\Gossamer\\Horus\\EventListeners\\TestListener' 
                    ),
                    array(
                        'event' => 'on_entry_point',
                        'listener' => 'Gossamer\\Horus\\Authorizations\\Listeners\\CheckServerCredentialsListener' 
                    )
                )
            )
        );
    }    
    
    private function getRequest() {
        $request = new Request();
        
        return $request;
    }
}
//listeners:
//        
//        - { 'event': 'request_start', 'listener': 'components\staff\listeners\LoadEmergencyContactsListener', 'datasource': 'datasource1' }
//        - { 'event': 'request_start', 'listener': 'core\eventlisteners\LoadListListener', 'datasource': 'datasource1', 'class': 'components\geography\models\ProvinceModel', 'cacheKey': 'Provinces' }
    