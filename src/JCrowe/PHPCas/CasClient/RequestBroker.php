<?php

namespace JCrowe\PHPCas\CasClient;


use GuzzleHttp\Client as GuzzleClient;
use JCrowe\PHPCas\CasClient\Requests\AbstractRequest;
use JCrowe\PHPCas\CasClient\ResponseHandlers\BaseRequestHandler;
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

            $content = $response->getBody()->getContents();

            if ($handler = $this->getHandlerForRequest($request)) {

                $handler->handle($content);
            }

            return $this->requestResponseFactory->makeFromResponseBody($content);
        }

        return $this->requestResponseFactory->makeFailedResponse();
    }


    /**
     * Get the handler for the provided $request object
     *
     * @param AbstractRequest $request
     * @return BaseRequestHandler
     */
    public function getHandlerForRequest(AbstractRequest $request)
    {
        $className = get_class($request);

        if ($pos = strrpos($className, '\\')) {

            $className = substr($className, $pos + 1);
        }

        $handlerClass = 'JCrowe\\PHPCas\\CasClient\\ResponseHandler\\' . $className;

        return class_exists($handlerClass) ? new $handlerClass() : null;
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