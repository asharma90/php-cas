<?php

namespace JCrowe\PHPCas\Contracts;

use JCrowe\PHPCas\Server\ServerResponse;

interface CasCurlRequestInterface {


    /**
     * @param ServerResponse $response
     * @param $rawResponseData
     * @return ServerResponse
     */
    public function fillResponseObject(ServerResponse $response, $rawResponseData);

}