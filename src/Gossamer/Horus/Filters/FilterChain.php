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
 * Time: 11:10 PM
 */

namespace Gossamer\Horus\Filters;


use Gossamer\Horus\Http\HttpInterface;

class FilterChain
{
    private $filters = array();

    public function addFilter(AbstractFilter $filter) {
        $this->filters[] = $filter;
    }

    public function execute(HttpInterface $request, HttpInterface $response, FilterChain &$chain) {
        $filter = array_shift($chain);
        $filter->execute($request, $response, $chain);
    }
}