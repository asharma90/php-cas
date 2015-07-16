<?php

namespace JCrowe\PHPCas;


class CasXMLParser {


    /**
     * @param $xml
     * @return array
     */
    public function parse($xml)
    {
        if ($this->isXMLResponse($xml)) {

            return $this->createArrayFromXml($xml);
        }
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
     * @param $xml
     * @return array
     */
    protected function createArrayFromXml($xml)
    {
        $dom = $this->loadIntoDom($xml);

        return $this->domToArray($dom);

    }


    /**
     * @param $xml
     * @return \DOMDocument
     */
    protected function loadIntoDom($xml)
    {
        $dom = new \DOMDocument();
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($xml);

        return $dom;
    }


    /**
     * From http://stackoverflow.com/questions/14553547/what-is-the-best-php-dom-2-array-function
     *
     * @param \DomDocument $root
     * @return array
     */
    protected function domToArray($root)
    {
        $result = [];

        if ($root->hasAttributes()) {
            $attrs = $root->attributes;
            foreach ($attrs as $attr) {
                $name = str_replace('cas:', '', $attr->name);
                $result['attributes'][$name] = $attr->value;
            }
        }

        if ($root->hasChildNodes()) {
            $children = $root->childNodes;
            if ($children->length == 1) {
                $child = $children->item(0);
                if ($child->nodeType == XML_TEXT_NODE) {
                    $result['value'] = $child->nodeValue;
                    return count($result) == 1
                        ? $result['value']
                        : $result;
                }
            }

            $groups = [];

            foreach ($children as $child) {
                $childName = str_replace('cas:', '', $child->nodeName);
                if (!isset($result[$childName])) {
                    $result[$childName] = $this->domToArray($child);
                } else {
                    if (!isset($groups[$childName])) {
                        $result[$childName] = [$result[$childName]];
                        $groups[$childName] = 1;
                    }
                    $result[$childName][] = $this->domToArray($child);
                }
            }
        }

        return $result;
    }

}