<?php

namespace JCrowe\PHPCas\Http;


use JCrowe\PHPCas\Contracts\CookieJarContract;

class CookieJar implements CookieJarContract {

    /**
     * @var array
     */
    protected $cookieSetInThisRequest = [];

    /**
     * Forget the cookie
     *
     * @param $name
     * @return mixed
     */
    public function forget($name)
    {
        unset($this->cookieSetInThisRequest[$name]);
        unset($_COOKIE[$name]);

        setcookie($name, null, -1);
    }

    /**
     * Get the cookie
     *
     * @param $name
     * @return mixed
     */
    public function get($name)
    {
        if (isset($this->cookieSetInThisRequest[$name])) {
            return $this->cookieSetInThisRequest[$name];
        }

        return isset($_COOKIE[$name]) ? $_COOKIE[$name] : null;
    }

    /**
     * Set a cookie to the response
     *
     * @param $name
     * @param $data
     * @param $ttl
     * @return mixed
     */
    public function set($name, $data, $ttl)
    {
        $this->cookieSetInThisRequest[$name] = $data;

        setcookie($name, $data, $ttl);
    }

    /**
     * @param $name
     * @return mixed
     */
    public function has($name)
    {
        return !is_null($this->get($name));
    }


}