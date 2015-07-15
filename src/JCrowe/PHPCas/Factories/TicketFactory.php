<?php

namespace JCrowe\PHPCas\Factories;


use JCrowe\PHPCas\Http\HttpRequest;
use JCrowe\PHPCas\Tickets\CasTicket;

class TicketFactory {


    /**
     * @return CasTicket
     */
    public function make()
    {
        return new CasTicket();
    }


    /**
     * @param HttpRequest $request
     * @return CasTicket
     */
    public function makeFromRequest(HttpRequest $request)
    {
        $ticket = $this->make();
        $ticket->setTicket($request->get('ticket'));

        return $ticket;
    }

}