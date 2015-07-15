<?php

namespace JCrowe\PHPCas\CasClient;


use GuzzleHttp\Client as GuzzleClient;
use JCrowe\PHPCas\CasClient\Requests\AbstractRequest;
use JCrowe\PHPCas\Contracts\RedirectorContract;
use JCrowe\PHPCas\Factories\RequestResponseFactory;
use JCrowe\PHPCas\Server\CasServerConfigs;
use JCrowe\PHPCas\Server\ServerResponse;

class RequestBroker {


    /**
     * @var RedirectorContract
     */
    protected $redirector;


    /**
     * @var CasServerConfigs
     */
    protected $configs;


    /**
     * @var GuzzleClient
     */
    protected $client;


    /**
     * @var RequestResponseFactory
     */
    protected $requestResponseFactory;

    /**
     * @param RedirectorContract $redirector
     * @param CasServerConfigs $configs
     */
    public function __construct(RedirectorContract $redirector, CasServerConfigs $configs, GuzzleClient $client, RequestResponseFactory $responseFactory)
    {
        $this->redirector = $redirector;
        $this->configs = $configs;
        $this->client = $client;
        $this->requestResponseFactory = $responseFactory;
    }


    /**
     * Send the client to the $request url
     *
     * @param AbstractRequest $request
     * @return mixed
     */
    public function request(AbstractRequest $request)
    {
        $url = $this->buildUrlFromRequestObject($request);

        return $this->redirector->go($url);
    }


    /**
     * Make a curl request to the SSO server
     *
     * @param AbstractRequest $request
     * @return ServerResponse
     */
    public function call(AbstractRequest $request)
    {
        $url = $this->buildUrlFromRequestObject($request);

        $response = $this->client->get($url);

        if ($response->getStatusCode() === 200) {

            return $this->requestResponseFactory->makeFromResponseBody($response->getBody()->getContents());
        }

        return $this->requestResponseFactory->makeFailedResponse();

    }


    /**
     * Build a request url from the request object
     *
     * @param AbstractRequest $request
     * @return string
     */
    protected function buildUrlFromRequestObject(AbstractRequest $request)
    {
        return $this->configs->getBaseUrl() . '/' . $request->getUri() . '?' . http_build_query($request->getParamsArray());
    }

}