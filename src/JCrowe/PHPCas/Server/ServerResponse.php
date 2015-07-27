<?php

namespace JCrowe\PHPCas\Server;


class ServerResponse {


    /**
     * @var mixed
     */
    protected $data;


    /**
     * @var bool
     */
    protected $isValidResponse;


    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }


    /**
     * @param $param
     * @return bool
     */
    public function has($param)
    {
        return isset($this->getData()[$param]);
    }


    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function get($param, $default = null)
    {
        return $this->has($param) ? $this->getData()[$param] : $default;
    }


    /**
     * Mark the response as invalid
     */
    public function markInvalid()
    {
        $this->isValidResponse = false;
    }


    /**
     * Mark the response as valid
     */
    public function markValid()
    {
        $this->isValidResponse = true;
    }


    /**
     * @return mixed
     */
    public function isValid()
    {
        return $this->isValidResponse;
    }
}