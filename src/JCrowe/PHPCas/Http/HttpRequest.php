<?php

namespace JCrowe\PHPCas\Http;


use JCrowe\PHPCas\Contracts\HttpRequestContract;


class HttpRequest implements HttpRequestContract {

    /**
     * Get the param from the request
     *
     * @param $paramName
     * @param null $default
     */
    public function get($paramName, $default = null)
    {
        return isset($_REQUEST[$paramName]) ? $_REQUEST[$paramName] : $default;
    }

    /**
     * Return all of the request parameters as an associative array
     *
     * @return array
     */
    public function toArray()
    {
        return isset($_REQUEST) ? $_REQUEST : [];
    }


}