<?php

namespace JCrowe\PHPCas\CasClient\Requests;


use JCrowe\PHPCas\CasClient\Routes;

class P3ProxyValidateRequest extends ProxyValidateRequest {


    /**
     * @param $service
     * @param $ticket
     * @param null $pgtUrl
     * @param null $renew
     */
    public function __construct($service, $ticket, $pgtUrl = null, $renew = null)
    {
        parent::__construct($service, $ticket, $pgtUrl, $renew);

        $this->setUri(Routes::PROXY_VALIDATE_V3);
    }

}