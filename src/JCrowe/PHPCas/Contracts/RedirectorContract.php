<?php

namespace JCrowe\PHPCas\Contracts;


interface RedirectorContract {


    /**
     * Redirect to the $to domain
     *
     * @param $to
     * @return mixed
     */
    public function go($to);

}