<?php

namespace JCrowe\PHPCas\Contracts;


interface CookieJarContract {


    /**
     * Set a cookie to the response
     *
     * @param $name
     * @param $data
     * @param $ttl
     * @return void
     */
    public function set($name, $data, $ttl);


    /**
     * Forget the cookie
     *
     * @param $name
     * @return void
     */
    public function forget($name);


    /**
     * Get the cookie
     *
     * @param $name
     * @return mixed
     */
    public function get($name);


    /**
     * @param $name
     * @return bool
     */
    public function has($name);
}