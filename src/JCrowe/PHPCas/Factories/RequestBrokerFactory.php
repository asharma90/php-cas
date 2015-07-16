<?php

namespace JCrowe\PHPCas\Factories;


use GuzzleHttp\Client;
use JCrowe\PHPCas\CasClient\RequestBroker;
use JCrowe\PHPCas\Http\Redirector;
use JCrowe\PHPCas\Server\CasServerConfigs;

class RequestBrokerFactory {


    public function make(CasServerConfigs $configs)
    {
        $redirector = new Redirector();
        $client = new Client();
        $responseFactory = new RequestResponseFactory();

        return new RequestBroker($redirector, $configs, $client, $responseFactory);
    }



}