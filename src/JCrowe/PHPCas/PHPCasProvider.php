<?php

namespace JCrowe\PHPCas;


use Illuminate\Encryption\Encrypter;
use JCrowe\PHPCas\CasClient\RequestBroker;
use JCrowe\PHPCas\CasClient\Requests\LoginRequest;
use JCrowe\PHPCas\CasClient\Requests\LogoutRequest;
use JCrowe\PHPCas\CasClient\Requests\ServiceValidateRequest;
use JCrowe\PHPCas\CasClient\Requests\ValidateRequest;
use JCrowe\PHPCas\Contracts\CookieJarContract;
use JCrowe\PHPCas\Factories\ConfigsFactory;
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
     * @var Encrypter
     */
    protected $encrypter;


    /**
     * @param RequestBrokerFactory $brokerFactory
     * @param RequestResponseFactory $responseFactory
     */
    public function __construct(
        ConfigsFactory $configsFactory,
        RequestBrokerFactory $brokerFactory,
        RequestResponseFactory $responseFactory,
        HttpRequest $httpRequest,
        CookieJarContract $cookieJar,
        Encrypter $encrypter
    )
    {
        $this->broker = $brokerFactory->make($configsFactory->getFromConfigFile());
        $this->responseFactory = $responseFactory;
        $this->httpRequest = $httpRequest;
        $this->cookieJar = $cookieJar;
        $this->encrypter = $encrypter;
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


    /**
     * Check if the ticket is still valid
     *
     * @param ValidateRequest $validateRequest
     * @return bool
     */
    public function validate(ValidateRequest $validateRequest)
    {
        if ($response = $this->broker->call($validateRequest)) {

            return $this->responseFactory->makeFromValidateResponse($response)->isValid();
        }

        return false;
    }


    /**
     * Check if the current client is authenticated
     *
     * @return mixed
     */
    public function isValidated()
    {
        return (bool) $this->getAuthenticatedUser();
    }


    /**
     * validate the token in the URI
     *
     * @param ServiceValidateRequest $validateRequest
     * @return bool
     */
    public function serviceValidate(ServiceValidateRequest $validateRequest)
    {
        if ($response = $this->broker->call($validateRequest)) {

            if ($response->isValid()) {

                $user = $response->getData()['user'];

                $this->cookieJar->set('cas_authenticated', $this->encrypt($user), 0);

                return true;
            }
        }

        $this->cookieJar->forget('cas_authenticated');

        return false;
    }


    /**
     * Get the authenticated user data
     *
     * @return null|string
     */
    public function getAuthenticatedUser()
    {

        if ($this->cookieJar->has('cas_authenticated')) {

            return $this->decrypt($this->cookieJar->get('cas_authenticated'));
        }

        return null;
    }


    /**
     * Encrypt the provided data
     *
     * @param $data
     */
    public function encrypt($data)
    {
        return $this->encrypter->encrypt($data);
    }


    /**
     * @param $data
     * @return string
     */
    public function decrypt($data)
    {
        return $this->encrypter->decrypt($data);
    }

}