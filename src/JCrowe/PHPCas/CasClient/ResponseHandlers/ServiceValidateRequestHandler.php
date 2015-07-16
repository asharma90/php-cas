<?php

namespace JCrowe\PHPCas\CasClient\ResponseHandlers;


use JCrowe\PHPCas\CasClient\Requests\AbstractRequest;
use JCrowe\PHPCas\Server\ServerResponse;

class ServiceValidateRequestHandler extends BaseRequestHandler {


    /**
     * @param ServerResponse $response
     * @param AbstractRequest $request
     * @return ServerResponse
     */
    public function handle(ServerResponse $response, AbstractRequest $request)
    {
        $data = $this->parseXML($response->getData());

        if (isset($data['serviceResponse']['authenticationSuccess'])) {

            $response->markValid();
            $response->setData($data['serviceResponse']['authenticationSuccess']);

        } else {
            $response->markInvalid();
        }

        return $response;
    }

}