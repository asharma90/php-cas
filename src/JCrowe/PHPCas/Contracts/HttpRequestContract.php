<?php

namespace JCrowe\PHPCas\Contracts;


interface HttpRequestContract {


    /**
     * Get the param from the request
     *
     * @param $paramName
     * @param null $default
     */
    public function get($paramName, $default = null);


    /**
     * Check if the request has $paramName
     *
     * @param $paramName
     * @return bool
     */
    public function has($paramName);


    /**
     * Return all of the request parameters as an associative array
     *
     * @return array
     */
    public function toArray();

}