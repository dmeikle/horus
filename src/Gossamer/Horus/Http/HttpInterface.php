<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/28/2017
 * Time: 6:48 PM
 */

namespace Gossamer\Horus\Http;


interface HttpInterface
{
   
    public function setAttribute($key, $value);

    public function getAttribute($key);
}