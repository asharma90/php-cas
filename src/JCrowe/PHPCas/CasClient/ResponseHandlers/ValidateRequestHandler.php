<?php

namespace JCrowe\PHPCas\CasClient\ResponseHandlers;


class ValidateRequestHandler extends BaseRequestHandler {


    public function handle($response)
    {
        $data = $this->parseXML($response);

        var_dump($data);exit;
    }

}