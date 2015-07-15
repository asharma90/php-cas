<?php

namespace JCrowe\PHPCas\CasClient\Requests;


use JCrowe\PHPCas\CasClient\Routes;

class ServiceValidateRequest extends AbstractRequest {

    /**
     * @var mixed
     */
    protected $pgtUrl;


    /**
     * @param $service
     * @param $ticket
     * @param null $pgtUrl
     * @param null $renew
     */
    public function __construct($service, $ticket, $pgtUrl = null, $renew = null)
    {
        $this->setService($service);
        $this->setTicket($ticket);
        $this->setRenew($renew);
        $this->setUri(Routes::SERVICE_VALIDATE);

        $this->pgtUrl = $pgtUrl;
    }

}