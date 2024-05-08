<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class DisplayGroup implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $group;
    public $user_joined;
    public $user;
    public function __construct($group, $user_joined)
    {
        $this->user_joined = $user_joined;
        $this->group = $group;
        $this->user = Auth::user();
    }

    public function broadcastOn()
    {
        return new PrivateChannel("displayGroup." . $this->user_joined);
    }
}
