<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 2/28/2017
 * Time: 6:46 PM
 */

namespace Gossamer\Horus\Http;


class Response implements HttpInterface
{
    protected $attributes = array();

    public function setAttribute($key, $value) {
        $this->attributes[$key] = $value;
    }

    public function getAttribute($key) {
        if(!array_key_exists($key, $this->attributes)) {
            return null;
        }

        return $this->attributes[$key];
    }

    public function getAttributes() {
        return $this->attributes;
    }
}