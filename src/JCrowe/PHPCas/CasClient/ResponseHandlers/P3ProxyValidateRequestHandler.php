<?php

namespace JCrowe\PHPCas\CasClient\ResponseHandlers;


use JCrowe\PHPCas\CasXMLParser;
use JCrowe\PHPCas\Contracts\RequestHandlerContract;

class P3ProxyValidateRequestHandler extends BaseRequestHandler {


    public function handle($response)
    {
        $data = $this->parseXML($response);

        var_dump($data);exit;
    }


}