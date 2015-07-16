<?php

namespace JCrowe\PHPCas\CasClient\Requests;


use JCrowe\PHPCas\CasClient\Routes;

class P3ServiceValidateRequest extends ServiceValidateRequest {


    /**
     * @param $service
     * @param $ticket
     * @param null $pgtUrl
     * @param null $renew
     */
    public function __construct($service, $ticket, $pgtUrl = null, $renew = null)
    {
        parent::__construct($service, $ticket, $pgtUrl, $renew);

        $this->setUri(Routes::SERVICE_VALIDATE_V3);
    }

}