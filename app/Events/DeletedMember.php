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

class DeletedMember implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $member;
    public $user;
    public $group_id;
    public function __construct($member,$group_id)
    {
        $this->member = $member;
        $this->user = Auth::user();
        $this->group_id = $group_id;
    }

    public function broadcastOn()
    {
        return new PrivateChannel("deleteMember.".$this->member);

    }
}
