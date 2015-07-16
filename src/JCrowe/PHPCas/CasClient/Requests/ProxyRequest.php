<?php

namespace JCrowe\PHPCas\CasClient\Requests;


use JCrowe\PHPCas\CasClient\Routes;

class ProxyRequest extends AbstractRequest {


    /**
     * the proxy-granting ticket acquired by the service during service ticket or proxy ticket validation.
     *
     * @var string
     */
    protected $pgt;

    /**
     * The service identifier of the back-end service. Note that not all back-end services are web services
     * so this service identifier will not always be an URL. However, the service identifier specified here
     * MUST match the service parameter specified to /proxyValidate upon validation of the proxy ticket.
     *
     * @var string
     */
    protected $targetService;


    public function __construct($pgt, $targetService)
    {
        $this->pgt = $pgt;
        $this->targetService = $targetService;
        $this->setUri(Routes::PROXY);
    }

}