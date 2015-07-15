<?php

namespace JCrowe\PHPCas\Factories;


use JCrowe\PHPCas\Http\HttpRequest;
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


    /**
     * @param $responseBody
     */
    public function makeFromResponseBody($responseBody)
    {
        var_dump($responseBody);exit;
    }


    public function makeFromRequest(HttpRequest $request)
    {
        $response = $this->make();

        if ($ticket = $request->get('ticket')) {
            $response->setData(['ticket' => $ticket]);
            $response->markValid();
        }

        return $response;
    }


    public function makeFromValidateResponse($responseBody)
    {
        var_dump($responseBody);exit;
    }


    /**
     * @return ServerResponse
     */
    public function makeFailedResponse()
    {
        $response = $this->make();

        return $response;
    }


}