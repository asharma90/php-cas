<?php

namespace JCrowe\PHPCas\CasClient\Requests;


abstract class AbstractRequest {


    /**
     * The URI destination on the CAS SSO server for this request
     *
     * @var string
     */
    protected $uri;


    /**
     * The service the client is requesting access to
     *
     * @var string|null
     */
    protected $service;

    /**
     * If this parameter is set, single sign-on will be bypassed.
     * In this case, CAS will require the client to present credentials
     * regardless of the existence of a single sign-on session with CAS.
     * This parameter is not compatible with the gateway parameter.
     * Services redirecting to the /login URI and login form views posting
     * to the /login URI SHOULD NOT set both the renew and gateway request
     * parameters.
     *
     * Behavior is undefined if both are set.
     * It is RECOMMENDED that CAS implementations ignore the gateway parameter if renew is set.
     * It is RECOMMENDED that when the renew parameter is set its value be “true”.
     *
     * @var string|null
     */
    protected $renew;


    /**
     * The method to be used when sending responses. While native HTTP redirects (GET)
     * may be utilized as the default method, applications that require a POST response
     * can use this parameter to indicate the method type. It is up to the CAS server
     * implementation to determine whether or not POST responses are supported.
     *
     * @var string|null
     */
    protected $responseMethod;


    /**
     * If this parameter is set, CAS will not ask the client for credentials.
     * If the client has a pre-existing single sign-on session with CAS,
     * or if a single sign-on session can be established through non-interactive
     * means (i.e. trust authentication), CAS MAY redirect the client to the
     * URL specified by the service parameter, appending a valid service ticket.
     * (CAS also MAY interpose an advisory page informing the client that a CAS
     * authentication has taken place.) If the client does not have a single sign-on
     * session with CAS, and a non-interactive authentication cannot be established,
     * CAS MUST redirect the client to the URL specified by the service parameter with
     * no “ticket” parameter appended to the URL. If the service parameter is not
     * specified and gateway is set, the behavior of CAS is undefined. It is RECOMMENDED
     * that in this case, CAS request credentials as if neither parameter was specified.
     * This parameter is not compatible with the renew parameter. Behavior is undefined
     * if both are set. It is RECOMMENDED that when the gateway parameter is set its
     * value be “true”.
     *
     * @var string|null
     */
    protected $gateway;


    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service
     */
    public function setService($service)
    {
        $this->service = $service;
    }

    /**
     * @return mixed
     */
    public function getRenew()
    {
        return $this->renew;
    }

    /**
     * @param mixed $renew
     */
    public function setRenew($renew)
    {
        $this->renew = (bool) $renew ? : null;
    }

    /**
     * @return mixed
     */
    public function getResponseMethod()
    {
        return $this->responseMethod;
    }

    /**
     * @param mixed $responseMethod
     */
    public function setResponseMethod($responseMethod)
    {
        $this->responseMethod = $responseMethod;
    }

    /**
     * @return mixed
     */
    public function getGateway()
    {
        return $this->gateway;
    }

    /**
     * @param mixed $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = (bool) $gateway ? : null;
    }

    /**
     * @return string
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * @param string $uri
     */
    public function setUri($uri)
    {
        $this->uri = $uri;
    }


    /**
     * Get a filtered list of params
     *
     * @return array
     */
    public function getParamsArray()
    {
        $params = get_object_vars($this);

        return array_filter($params);
    }

}