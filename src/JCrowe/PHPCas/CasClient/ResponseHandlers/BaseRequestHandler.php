<?php

namespace JCrowe\PHPCas\CasClient\ResponseHandlers;


use JCrowe\PHPCas\CasClient\Requests\AbstractRequest;
use JCrowe\PHPCas\CasXMLParser;
use JCrowe\PHPCas\Server\ServerResponse;

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
     * @return ServerResponse
     */
    abstract public function handle(ServerResponse $response, AbstractRequest $request);




}