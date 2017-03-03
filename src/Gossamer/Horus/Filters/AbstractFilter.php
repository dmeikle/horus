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
 * Time: 11:08 PM
 */

namespace Gossamer\Horus\Filters;


use Gossamer\Horus\Http\HttpInterface;
use Gossamer\Pesedget\Database\DatasourceFactory;
use Gossamer\Set\Utils\Container;

class AbstractFilter
{
    protected $datasourceFactory;

    protected $filterConfig;

    protected $container;

    public function __construct(FilterConfig $config) {
        $this->filterConfig = $config;
    }

    public function setDatasourceFactory(DatasourceFactory $datasourceFactory) {
        $this->datasourceFactory = $datasourceFactory;
    }

    public function setContainer(Container $container) {
        $this->container = $container;
    }

    public function execute(HttpInterface $request, HttpInterface $response, FilterChain $chain) {
        try {
            $chain->doFilter($request, $response);
        } catch (\Exception $e) {

        }
    }
}