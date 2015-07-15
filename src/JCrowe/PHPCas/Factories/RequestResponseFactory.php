<?php

namespace JCrowe\PHPCas\Factories;


use JCrowe\PHPCas\Server\ServerResponse;

class RequestResponseFactory {


    /**
     * Create an empty response object
     *
     * @return ServerResponse
     */
    public function make()
    {
        return new ServerResponse();
    }




    public function makeFromResponseBody($responseBody)
    {
        var_dump($responseBody);exit;
    }


    public function makeFailedResponse()
    {
        $response = $this->make();

        return $response;
    }


}