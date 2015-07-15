<?php

namespace JCrowe\PHPCas\Server;


class CasServerConfigs {


    /**
     * @var String
     */
    protected $host;


    /**
     * @var String
     */
    protected $port;


    /**
     * @var String
     */
    protected $baseUri;


    /**
     * @var String
     */
    protected $certificate;


    /**
     * Optionally build the configs object with array of configs
     *
     * @param array $configs
     */
    public function __construct(array $configs = [])
    {
        $this->setHost(isset($configs['host']) ? $configs['host'] : null);
        $this->setPort(isset($configs['port']) ? $configs['port'] : null);
        $this->setBaseUri(isset($configs['uri']) ? $configs['uri'] : null);
        $this->setCertificate(isset($configs['certificate']) ? $configs['certificate'] : null);
    }


    /**
     * @return String
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param String $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    /**
     * @return String
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param String $port
     */
    public function setPort($port)
    {
        $this->port = $port;
    }

    /**
     * @return String
     */
    public function getBaseUri()
    {
        return $this->baseUri;
    }

    /**
     * @param String $baseUri
     */
    public function setBaseUri($baseUri)
    {
        $this->baseUri = $baseUri;
    }

    /**
     * @return String
     */
    public function getCertificate()
    {
        return $this->certificate;
    }

    /**
     * @param String $certificate
     */
    public function setCertificate($certificate)
    {
        $this->certificate = $certificate;
    }


    /**
     * @return string
     */
    public function getBaseUrl()
    {
        return $this->getHost() . $this->getBaseUri();
    }



}