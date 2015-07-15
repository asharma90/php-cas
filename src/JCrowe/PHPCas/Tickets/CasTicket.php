<?php

namespace JCrowe\PHPCas\Tickets;


class CasTicket {

    /**
     * @var string
     */
    protected $ticket;


    /**
     * @param $ticket
     */
    public function __construct($ticket = null)
    {
        $this->setTicket($ticket);
    }


    /**
     * @param $ticket
     */
    public function setTicket($ticket)
    {
        $this->ticket = $ticket;
    }


    /**
     * @return string
     */
    public function getTicket()
    {
        return $this->ticket;
    }

}