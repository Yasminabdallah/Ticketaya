<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Event;
use Auth;
use App\Notification;

class EventSubscribers implements ShouldBroadcast
{
    public $message;
    public $user_id;
    public $notification_id;

    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($event_id , $toUser)
    {

      $event = Event::find($event_id);
      $this->message = "Event {$event->name} Has New informations don't miss it";
      $this->user_id = $toUser;
      $notification=  Notification::create([
        'user_id' => $this->user_id,
        'notify_type_id' => 2,
        'message'=>$this->message
   ]);
       $this->notification_id=$notification->id;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('event-subscriber_'.$this->user_id);

    }
}
