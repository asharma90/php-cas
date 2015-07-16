<?php

namespace JCrowe\PHPCas\Tickets;


abstract class AbstractCasTicket {

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
     * @return int
     */
    public function isValid()
    {
        return (bool) preg_match('/^[a-z0-9-]*/i$', $this->getTicket());
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