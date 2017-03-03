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
        $filter = $this->next();
        if($filter !== false) {
            $filter->execute($request, $response, $chain);
        }
        //exit gracefully
    }

    private function next() {
        if(count($this->filters) > 0) {
            return array_shift($this->filters);
        }

        return false;
    }
}