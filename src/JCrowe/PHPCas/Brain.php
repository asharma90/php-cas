<?php

namespace JCrowe\PHPCas;


use JCrowe\PHPCas\Contracts\CookieJarContract;
use JCrowe\PHPCas\Contracts\HttpRequestContract;

class Brain {


    /**
     * @var HttpRequestContract
     */
    protected $request;



    public function __construct(HttpRequestContract $request)
    {
        $this->request = $request;
    }


    /**
     * Check if the current request is a proxy validate request
     *
     * @return bool
     */
    public function isProxyValidateRequest()
    {
        return $this->request->has('pgtId') && $this->request->has('pgtIou');
    }


}