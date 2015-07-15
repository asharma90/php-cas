<?php

namespace JCrowe\PHPCas\CasClient\Requests;


use JCrowe\PHPCas\CasClient\Routes;

class LogoutRequest extends AbstractRequest {


    /**
     * @param null $service
     */
    public function __construct($service = null)
    {
        $this->setService($service);
        $this->setUri(Routes::LOGOUT);
    }

}