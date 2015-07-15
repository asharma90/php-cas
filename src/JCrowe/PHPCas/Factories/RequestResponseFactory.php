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
        $response = $this->make();
        $response->setData($responseBody);
        $response->markValid();

        return $response;
    }


    /**
     * @param HttpRequest $request
     * @return ServerResponse
     */
    public function makeFromRequest(HttpRequest $request)
    {
        $response = $this->make();

        if ($ticket = $request->get('ticket')) {
            $response->setData($ticket);
            $response->markValid();
        }

        return $response;
    }


    /**
     * @param ServerResponse $response
     * @return ServerResponse
     */
    public function makeFromValidateResponse(ServerResponse $response)
    {
        $valid = strpos(strtolower($response->getData()), 'yes') !== false;

        if ($valid) {

            $response->markValid();
        } else {

            $response->markInvalid();
        }

        return $response;
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