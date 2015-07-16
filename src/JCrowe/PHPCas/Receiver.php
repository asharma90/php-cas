<?php

namespace JCrowe\PHPCas;

use JCrowe\PHPCas\CasClient\TicketBroker;
use JCrowe\PHPCas\Contracts\HttpRequestContract;
use JCrowe\PHPCas\Factories\TicketFactory;
use JCrowe\PHPCas\Tickets\AbstractCasTicket;

class Receiver {

    /**
     * @var HttpRequestContract
     */
    protected $request;


    /**
     * @var TicketFactory
     */
    protected $ticketFactory;


    /**
     * @var TicketBroker
     */
    protected $ticketBroker;


    /**
     * @param HttpRequestContract $request
     */
    public function __construct(HttpRequestContract $request, TicketFactory $ticketFactory, TicketBroker $broker)
    {
        $this->request = $request;
        $this->ticketFactory = $ticketFactory;
        $this->ticketBroker = $broker;

        $this->run();
    }


    /**
     * Handle reception of data from the SSO server via redirect
     */
    protected function run()
    {
        if ($ticket = $this->ticketFactory->makeFromRequest($this->request)) {

            $this->fulfillTicket($ticket);
        }
    }



    protected function fulfillTicket(AbstractCasTicket $ticket)
    {
        $this->ticketBroker->handle($ticket);
    }

}