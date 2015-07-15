<?php

namespace JCrowe\PHPCas\CasClient;


use JCrowe\PHPCas\Contracts\CookieJarContract;
use JCrowe\PHPCas\Contracts\TicketContract;

class TicketGrantingCookie {


    /**
     * @var CookieJarContract
     */
    protected $cookieJar;


    /**
     * @var TicketContract
     */
    protected $ticket;


    /**
     * @param CookieJarContract $cookieJar
     */
    public function __construct(CookieJarContract $cookieJar, TicketContract $ticket)
    {
        $this->cookieJar = $cookieJar;
        $this->ticket = $ticket;
    }


    protected function setTicketToCookieJar(TicketContract $ticket)
    {
        $this->cookieJar->set('cas_ticket', $ticket->getTicketCode(), 999);
    }



}