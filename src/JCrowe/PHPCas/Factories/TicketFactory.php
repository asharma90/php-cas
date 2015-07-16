<?php

namespace JCrowe\PHPCas\Factories;


use JCrowe\PHPCas\Http\HttpRequest;
use JCrowe\PHPCas\Tickets\AbstractCasTicket;
use JCrowe\PHPCas\Tickets\CasTicket;
use JCrowe\PHPCas\Tickets\LoginTicket;
use JCrowe\PHPCas\Tickets\ProxyGrantingTicket;
use JCrowe\PHPCas\Tickets\ProxyTicket;
use JCrowe\PHPCas\Tickets\ServiceTicket;
use JCrowe\PHPCas\Tickets\TicketGrantingTicket;

class TicketFactory {


    /**
     * @return AbstractCasTicket
     */
    public function makeFromTicketString($ticketString)
    {
        $prefix = str_split($ticketString, '-');

        if (isset($prefix[0])) {

            switch($prefix[0]) {

                case LoginTicket::TICKET_PREFIX:
                    $ticket = new LoginTicket();
                    break;

                case ProxyGrantingTicket::TICKET_PREFIX:
                    $ticket = new ProxyGrantingTicket();
                    break;

                case ProxyTicket::TICKET_PREFIX:
                    $ticket = new ProxyTicket();
                    break;

                case ServiceTicket::TICKET_PREFIX:
                    $ticket = new ServiceTicket();
                    break;

                case TicketGrantingTicket::TICKET_PREFIX:
                    $ticket= new TicketGrantingTicket();
                    break;

                default:
                    break;
            }
        }

        if (isset($ticket)) {

            $ticket->setTicket($ticketString);

            return $ticket;
        }

        return null;
    }


    /**
     * @param HttpRequest $request
     * @return AbstractCasTicket|null
     */
    public function makeFromRequest(HttpRequest $request)
    {

        if ($request->has('ticket')) {
            return $this->makeFromTicketString($request->get('ticket'));
        }

        return null;
    }

}