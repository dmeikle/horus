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
 * Time: 11:57 PM
 */

namespace tests\Gossamer\Horus\Filters;


use Gossamer\Horus\Filters\AbstractFilter;
use Gossamer\Horus\Filters\FilterChain;
use Gossamer\Horus\Http\HttpInterface;

class Filter1 extends AbstractFilter
{

    public function execute(HttpInterface $request, HttpInterface $response, FilterChain $chain) {
       echo "this is filter1\r\n";
        throw new \Exception('throwing exception 1');
            $chain->execute($request, $response, $chain);
    }

}