<?php

namespace JCrowe\PHPCas\CasClient\ResponseHandlers;


class P3ServiceValidateRequestHandler extends BaseRequestHandler {



    public function handle($response)
    {
        $data = $this->parseXML($response);

        var_dump($data);exit;
    }


}