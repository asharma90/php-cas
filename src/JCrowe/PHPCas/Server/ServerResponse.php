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

            $data = $this->parseXMLToJson($data);
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

            return (bool) $this->getXMLDocFromResponse($responseString);
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

        libxml_use_internal_errors(true);

        $doc = simplexml_load_string($responseString);

        if (!$doc) {

            libxml_clear_errors();

            return null;
        }

        return $doc;
    }


    /**
     * Parse the XML string to Json
     *
     * @param $xml
     * @return array
     */
    protected function parseXMLToJson($xml)
    {
        $xmlDoc = $this->getXMLDocFromResponse($xml);

        $json = json_encode($xmlDoc);

        return json_decode($json);
    }

}