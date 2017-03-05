<?php
/*
 *  This file is part of the Quantum Unit Solutions development package.
 *
 *  (c) Quantum Unit Solutions <http://github.com/dmeikle/>
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 */

/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/2/2017
 * Time: 11:07 PM
 */

namespace Gossamer\Horus\Filters;


use Gossamer\Horus\Datasources\DatasourceFactoryInterface;
use Gossamer\Horus\Http\HttpInterface;
use Gossamer\Neith\Logging\LoggingInterface;
use Gossamer\Pesedget\Database\DatasourceFactory;
use Gossamer\Set\Utils\Container;

class FilterDispatcher
{

    private $filterChain;

    private $logger;

    private $datasourceFactory;

    private $container;

    public function __construct(LoggingInterface $logger) {
        $this->filterChain = new FilterChain();
        $this->logger = $logger;
    }

    public function setContainer(Container $container) {
        $this->container = $container;
    }
    
    public function setDatasources(DatasourceFactory $datasourceFactory) {
        $this->datasourceFactory = $datasourceFactory;
    }

    public function setFilters(array $filterConfig) {
        foreach($filterConfig as $filterParams) {
            $this->addFilter($filterParams);
        }
    }

    public function setFilterConfigurationPath($path, $keys = null) {

        $config = $this->loadConfig($path);

        if(!is_null($keys)) {
            $keyList = explode('.', $keys);
            foreach($keyList as $key) {
                $config = $config[$key];
            }
        }
        $this->setFilters($config);
    }

    protected function addFilter($filterParams) {
        $filterName = $filterParams['filter'];
        $filter = new $filterName($this->getFilterConfiguration($filterParams));
        $filter->setContainer($this->container);
        
        $this->filterChain->addFilter($filter);
    }

    protected function getFilterConfiguration(array $filterParams) {
        $filterConfig = new FilterConfig($filterParams);
        
        return $filterConfig;
    }

    public function filterRequest(HttpInterface $request, HttpInterface $response) {
        try{
            $this->filterChain->execute($request, $response, $this->filterChain);
        }catch(\Exception $e) {
            $this->logger->addError($e->getMessage());
        }
    }
}