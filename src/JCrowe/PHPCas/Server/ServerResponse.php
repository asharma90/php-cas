<?php

namespace JCrowe\PHPCas\Server;


class ServerResponse {


    /**
     * @var mixed
     */
    protected $data;


    /**
     * @var bool
     */
    protected $isValidResponse;


    public function __construct()
    {

    }

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        if ($this->isXMLResponse($data)) {

            $data = $this->parseXMLToArray($data);
        }

        $this->data = $data;
    }


    /**
     * Mark the response as invalid
     */
    public function markInvalid()
    {
        $this->isValidResponse = false;
    }


    /**
     * Mark the response as valid
     */
    public function markValid()
    {
        $this->isValidResponse = true;
    }


    /**
     * @return mixed
     */
    public function isValid()
    {
        return $this->isValidResponse;
    }


    /**
     * @param $responseString
     * @return bool
     */
    public function isXMLResponse($responseString)
    {
        if (is_string($responseString)) {

            return !is_null($this->getXMLDocFromResponse($responseString));
        }

        return false;
    }


    /**
     * @param $responseString
     * @return null|\SimpleXMLElement
     */
    protected function getXMLDocFromResponse($responseString)
    {
        if (!is_string($responseString)) {
            return null;
        }

        $xml = str_replace(["\n", "\r", "\t"], '', $responseString);
        $xml = trim(str_replace('"', "'", $responseString));

//        libxml_use_internal_errors(true);

        $doc = simplexml_load_string($xml);

        if ($doc === false) {

            libxml_clear_errors();

            return null;
        }

        return $doc;
    }


    /**
     * Parse the XML string to Array
     *
     * @param $xml
     * @return array
     */
    protected function parseXMLToArray($xml)
    {
        $dataArray = [];
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;

        $dom->loadXML($xml);

        if ($dom->getElementsByTagName('authenticationFailure')->length) {
            // this was a failed call, set up the data accordingly

            $failureNode = $dom->getElementsByTagName('authenticationFailure')->item(0);

            $dataArray['message'] = $failureNode->nodeValue;
            $dataArray['error_code'] = $failureNode->getAttribute('code');

        } else if ($dom->getElementsByTagName('authenticationSuccess')->length) {
            // this was a successful call, set up the data!

            $user = $dom->getElementsByTagName('user')->item(0);

            $dataArray['user'] = $user->nodeValue;

            if ($dom->getElementsByTagName('proxyGrantingTicket')->length) {

                $dataArray['proxy_granting_ticket'] = $dom->getElementsByTagName('proxyGrantingTicket')->item(0)->nodeValue;
            }
        }

        return $dataArray;

    }

}