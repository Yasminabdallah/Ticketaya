<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

use App\User;
use App\Ticket;
use App\Notification;

class StatusTicketRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */

     public $BuyerId;
     public $message;
    public function __construct(  $requestedTicket,$is_accept)

    {
        
        $this->Ticket=Ticket::find($requestedTicket->ticket_id)->first();
        $this->TicketName=$this->Ticket->name;
        $this->Quantity=$requestedTicket->quantity;
        $this->BuyerId=$requestedTicket->requester_id;
        
        $this->SellerId=User::find($requestedTicket->user_id)->first();
        $this->sellerName=$this->SellerId->name;
        if ($is_accept=="true"){

        $this->message = "{$this->sellerName} accept  Your ticket {$this->TicketName} with quantity= {$this->Quantity}";
        }
        else{
            $this->message = "{$this->sellerName} cancel Your ticket {$this->TicketName} with quantity= {$this->Quantity}";

        }

        Notification::create([
            
                        'user_id' => $BuyerId,
                        'notify_type_id' => 2,
                        'message'=>$this->message
                   ]);
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return ('status-tickedrequest_'.$this->BuyerId);
    }
}
