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

class FilterDispatcher
{

    private $filterChain;

    private $logger;

    private $datasourceFactory;
    
    public function __construct(LoggingInterface $logger) {
        $this->filterChain = new FilterChain();
        $this->logger = $logger;
    }
    
    public function setDatasources(DatasourceFactory $datasourceFactory) {
        $this->datasourceFactory = $datasourceFactory;
    }

    public function setFilters(array $filterConfig) {
        foreach($filterConfig as $filterParams) {
            $this->addFilter($filterParams);
        }
    }

    protected function addFilter($filterParams) {
        $filterName = $filterParams['filter'];
        $filter = new $filterName();
        $this->filterChain->addFilter($filter);
    }

    protected function getFilterConfiguration(array $filterParams) {

    }

    public function filterRequest(HttpInterface $request, HttpInterface $response) {
        try{
            $this->filterChain->execute($request, $response, $this->filterChain);
        }catch(\Exception $e) {
            $this->logger->addError($e->getMessage());
        }
    }
}