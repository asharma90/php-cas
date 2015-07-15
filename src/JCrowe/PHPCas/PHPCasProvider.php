<?php

namespace JCrowe\PHPCas;


use JCrowe\PHPCas\CasClient\RequestBroker;
use JCrowe\PHPCas\CasClient\Requests\LoginRequest;
use JCrowe\PHPCas\CasClient\Requests\LogoutRequest;
use JCrowe\PHPCas\CasClient\Requests\ValidateRequest;
use JCrowe\PHPCas\Contracts\CookieJarContract;
use JCrowe\PHPCas\Factories\RequestBrokerFactory;
use JCrowe\PHPCas\Factories\RequestResponseFactory;
use JCrowe\PHPCas\Http\HttpRequest;

class PHPCasProvider {


    /**
     * @var RequestBroker
     */
    protected $broker;


    /**
     * @var RequestResponseFactory
     */
    protected $responseFactory;


    /**
     * @var HttpRequest
     */
    protected $httpRequest;


    /**
     * @var CookieJarContract
     */
    protected $cookieJar;


    /**
     * @param RequestBrokerFactory $brokerFactory
     * @param RequestResponseFactory $responseFactory
     */
    public function __construct(
        RequestBrokerFactory $brokerFactory,
        RequestResponseFactory $responseFactory,
        HttpRequest $httpRequest,
        CookieJarContract $cookieJar
    )
    {
        $this->broker = $brokerFactory->make();
        $this->responseFactory = $responseFactory;
        $this->httpRequest = $httpRequest;
        $this->cookieJar = $cookieJar;

        $this->checkForTicket();
    }


    /**
     * @param LoginRequest $loginRequest
     * @return mixed
     */
    public function login(LoginRequest $loginRequest)
    {
        return $this->broker->request($loginRequest);
    }


    /**
     * Log the user out
     *
     * @param LogoutRequest $logoutRequest
     */
    public function logout(LogoutRequest $logoutRequest)
    {
        return $this->broker->request($logoutRequest);
    }



    public function validate(ValidateRequest $validateRequest)
    {
        if ($response = $this->broker->call($validateRequest)) {

            return $this->responseFactory->makeFromValidateResponse($response->getData());
        }

        var_dump($response, "Fail");exit;
    }


    public function getUser()
    {
        if ($ticket = $this->cookieJar->get('cas_ticket')) {
            return $this->broker->validateUser($ticket);
        }

        return null;
    }


    /**
     * Check if a ticket is in the URI and if so set it in a cookie
     */
    protected function checkForTicket()
    {
        if ($ticket = $this->httpRequest->get('ticket')) {
            $this->cookieJar->set('cas_ticket', $ticket, 0);
        }
    }

}