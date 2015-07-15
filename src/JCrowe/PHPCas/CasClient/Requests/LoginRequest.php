<?php

namespace JCrowe\PHPCas\CasClient\Requests;


use JCrowe\PHPCas\CasClient\Routes;

class LoginRequest extends AbstractRequest {


    /**
     * Create the request with params
     *
     * @param null $service
     * @param null $renew
     * @param null $gateway
     * @param null $warn
     * @param null $responseMethod
     */
    public function __construct(
        $service = null,
        $renew = null,
        $gateway = null,
        $responseMethod = null
    )
    {
        $this->setService($service);
        $this->setRenew($renew);
        $this->setGateway($gateway);
        $this->setResponseMethod($responseMethod);
        $this->setUri(Routes::LOGIN);
    }

}