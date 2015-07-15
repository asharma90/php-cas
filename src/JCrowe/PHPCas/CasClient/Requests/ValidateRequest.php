<?php

namespace JCrowe\PHPCas\CasClient\Requests;


use JCrowe\PHPCas\CasClient\Routes;

class ValidateRequest extends AbstractRequest {


    public function __construct($service, $ticket, $renew = null)
    {
        $this->setService($service);
        $this->setTicket($ticket);
        $this->setRenew($renew);
        $this->setUri(Routes::VALIDATE);
    }

}