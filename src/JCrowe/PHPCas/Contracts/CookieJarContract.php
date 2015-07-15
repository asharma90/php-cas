<?php

namespace JCrowe\PHPCas\Contracts;


interface CookieJarContract {


    /**
     * Set a cookie to the response
     *
     * @param $name
     * @param $data
     * @param $ttl
     * @return mixed
     */
    public function set($name, $data, $ttl);


    /**
     * Forget the cookie
     *
     * @param $name
     * @return mixed
     */
    public function forget($name);


    /**
     * Get the cookie
     *
     * @param $name
     * @return mixed
     */
    public function get($name);

}