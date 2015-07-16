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
     * Check if the request has $paramName
     *
     * @param $paramName
     * @return bool
     */
    public function has($paramName)
    {
        return !is_null($this->get($paramName));
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