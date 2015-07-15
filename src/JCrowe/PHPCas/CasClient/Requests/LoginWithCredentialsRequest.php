<?php

namespace JCrowe\PHPCas\CasClient\Requests;


class LoginWithCredentialsRequest extends LoginRequest {

    /**
     * if this parameter is set, single sign-on MUST NOT be transparent.
     * The client MUST be prompted before being authenticated to another service.
     *
     * @var bool
     */
    protected $warn;


    /**
     * @var string|null
     */
    protected $username;

    /**
     * @var string|null
     */
    protected $password;


    /**
     * @var bool
     */
    protected $rememberMe;


    /**
     * @param null $username
     * @param null $password
     * @param null $ticket
     * @param bool $rememberMe
     * @param null $warn
     * @param null $service
     * @param null $renew
     * @param null $gateway
     * @param null $responseMethod
     */
    public function __construct(
        $username,
        $password,
        $ticket,
        $rememberMe = false,
        $warn = null,
        $service = null,
        $renew = null,
        $gateway = null,
        $responseMethod = null
    )
    {
        $this->username = $username;
        $this->password = $password;
        $this->warn = $warn;
        $this->rememberMe = (bool) $rememberMe;
        $this->setTicket($ticket);

        parent::__construct($service, $renew, $gateway, $responseMethod);
    }

}