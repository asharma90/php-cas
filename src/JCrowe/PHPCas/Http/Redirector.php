<?php

namespace JCrowe\PHPCas\Http;


use JCrowe\PHPCas\Contracts\RedirectorContract;

class Redirector  implements  RedirectorContract {

    /**
     * Redirect to the $to domain
     *
     * @param $to
     * @return mixed
     */
    public function go($to)
    {
        header('Location: ' . $to);

        exit();
    }


}