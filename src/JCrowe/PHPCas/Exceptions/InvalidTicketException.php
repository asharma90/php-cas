<?php

namespace JCrowe\PHPCas\Exceptions;


class InvalidTicketException extends \Exception {


    public function __construct($ticket)
    {
        $this->message = "Ticket: " . $ticket . " is invalid.";
    }

}