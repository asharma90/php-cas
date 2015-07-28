<?php

namespace JCrowe\PHPCas;


use Illuminate\Encryption\Encrypter;
use JCrowe\PHPCas\CasClient\RequestBroker;
use JCrowe\PHPCas\CasClient\Requests\LoginRequest;
use JCrowe\PHPCas\CasClient\Requests\LogoutRequest;
use JCrowe\PHPCas\CasClient\Requests\ServiceValidateRequest;
use JCrowe\PHPCas\CasClient\Requests\ValidateRequest;
use JCrowe\PHPCas\Contracts\CookieJarContract;
use JCrowe\PHPCas\Exceptions\InvalidTicketException;
use JCrowe\PHPCas\Factories\ConfigsFactory;
use JCrowe\PHPCas\Factories\RequestBrokerFactory;
use JCrowe\PHPCas\Factories\RequestResponseFactory;
use JCrowe\PHPCas\Http\CookieJar;
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
     * @var Server\CasServerConfigs
     */
    protected $configs;


    /**
     * @param RequestBrokerFactory $brokerFactory
     * @param RequestResponseFactory $responseFactory
     */
    public function __construct(
        ConfigsFactory $configsFactory,
        RequestBrokerFactory $brokerFactory,
        RequestResponseFactory $responseFactory,
        HttpRequest $httpRequest,
        CookieJarContract $cookieJar
    )
    {
        $this->configs = $configsFactory->getFromConfigFile();
        $this->broker = $brokerFactory->make($this->configs);
        $this->responseFactory = $responseFactory;
        $this->httpRequest = $httpRequest;
        $this->cookieJar = $cookieJar;
        $this->encrypter = new Encrypter($this->configs->getKey(), 'AES-256-CBC');
    }


    /**
     * Create the provider class using the default objects
     *
     * @return PHPCasProvider
     */
    public static function make()
    {
        return new PHPCasProvider(
            new ConfigsFactory(),
            new RequestBrokerFactory(),
            new RequestResponseFactory(),
            new HttpRequest(),
            new CookieJar());
    }


    /**
     * Do a login to the specification of the $request
     *
     * @param LoginRequest $request
     * @return mixed
     */
    public function login(LoginRequest $request)
    {
        return $this->broker->request($request);
    }

    /**
     * Do the basic cas authentication which involves the following steps:
     *
     * 1) Redirect the user to the CAS sso server
     * 2) User authenticates with the CAS sso server if not already logged in
     * 3) CAS server redirects user back to the provided $callbackURL
     *
     * @param null $callbackUrl
     * @return mixed
     */
    public function doBasicCasAuthentication($callbackUrl, $respondWithPostRequest = false)
    {
        $method = $respondWithPostRequest ? 'POST' : null;

        $loginRequest = new LoginRequest($callbackUrl, null, null, $method);

        return $this->login($loginRequest);
    }


    /***
     * Do a basic cas authentication without redirecting back to this app
     *
     * 1) Redirect user to the CAS sso server
     * 2) User authenticates with the CAS sso server if not already logged in
     * 3) User flow is dictated by the CAS server
     *
     * @return mixed
     */
    public function doBasicCasAuthenticationWithoutRedirectingBack()
    {
        $loginRequest = new LoginRequest();

        return $this->login($loginRequest);
    }


    /**
     * Go to the cas authentication sso server and force user to input credentials
     * regardless of their current logged in status.
     *
     * 1) Redirect the user to the CAS sso server
     * 2) User authenticates with the CAS sso server regardless of current logged in status
     * 3) CAS server redirects user back to the provided $callbackURL
     *
     * @param $callbackUrl
     * @param bool $respondWithPostRequest
     * @return mixed
     */
    public function makeUserLoginOrReAuthenticate($callbackUrl, $respondWithPostRequest = false)
    {
        $method = $respondWithPostRequest ? 'POST' : null;

        $loginRequest = new LoginRequest($callbackUrl, true, null, $method);

        return $this->login($loginRequest);
    }


    /**
     * Go to the CAS authentication sso server and if the user is already logged in return back with
     * the logged in ticket, otherwise return back without one
     *
     * @param $callbackUrl
     * @param bool $respondWithPostRequest
     * @return mixed
     */
    public function authenticateUserOnlyIfAlreadyLoggedIn($callbackUrl, $respondWithPostRequest = false)
    {
        $method = $respondWithPostRequest ? 'POST' : null;

        $loginRequest = new LoginRequest($callbackUrl, false, true, $method);

        return $this->login($loginRequest);
    }


    /**
     * Handle the login response by validating the ticket and setting a cookie on the client
     *
     * @param $callbackUrlUsedInLoginRequest
     * @param $ticket
     * @param int $loginCookieTtlInMinutes
     * @return bool
     * @throws InvalidTicketException
     */
    public function handleLoginResponse($callbackUrlUsedInLoginRequest, $ticket)
    {
        if (!$this->serviceValidate(new ServiceValidateRequest($callbackUrlUsedInLoginRequest, $ticket))) {

            throw new InvalidTicketException($ticket);
        }

        return true;
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
     * Validate the token in the URI
     *
     * @param ServiceValidateRequest $validateRequest
     * @param int $loginCookieTtlInMinutes
     * @return bool
     */
    public function serviceValidate(ServiceValidateRequest $validateRequest)
    {
        if ($response = $this->broker->call($validateRequest)) {

            if ($response->has('user')) {

                $user = $response->get('user');

                $this->cookieJar->set('cas_authenticated', $this->encrypt($user), $this->getLoginCookieTTL());

                if ($response->has('proxyGrantingTicket')) {

                    // we need to fire off a proxy grant request now
                    $this->getProxyGrant($response->get('proxyGrantingTicket'));
                }

                return true;
            }
        }

        $this->cookieJar->forget('cas_authenticated');

        return false;
    }


    /**
     * Unsupported proxy granting ticket
     *
     * @param $proxyGrantingTicket
     */
    public function getProxyGrant($proxyGrantingTicket)
    {
        // todo: Implement proxy granting
    }


    /**
     * Get the authenticated user data
     *
     * @return null|string
     */
    public function getAuthenticatedUser()
    {

        if ($this->cookieJar->has('cas_authenticated')) {

            $this->cookieJar->set('cas_authenticated', $this->cookieJar->get('cas_authenticated'), $this->getLoginCookieTTL());

            return $this->decrypt($this->cookieJar->get('cas_authenticated'));
        }

        return null;
    }


    /**
     * Get the logged in cookie ttl from the config
     */
    public function getLoginCookieTTL()
    {
        return $this->configs->getLoggedInCookieTTL();
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