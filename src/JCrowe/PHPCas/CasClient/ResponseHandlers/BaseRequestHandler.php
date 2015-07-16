<?php

namespace JCrowe\PHPCas\CasClient\ResponseHandlers;


use JCrowe\PHPCas\CasXMLParser;

abstract class BaseRequestHandler {

    /**
     * @var CasXMLParser
     */
    protected $xmlParser;



    public function __construct()
    {
        $this->xmlParser = new CasXMLParser();
    }


    /**
     * @param $xml
     * @return array
     */
    public function parseXML($xml)
    {
        return $this->xmlParser->parse($xml);
    }


    /**
     * @param $response
     * @return mixed
     */
    abstract public function handle($response);




}