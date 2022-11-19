<?php

namespace App\Events\LiveStream;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\LiveStream;

class DeleteChatComment implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $livestream;
    public $chatmsg;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LiveStream $livestream, $chatmsg)
    {
        $this->livestream = $livestream;
        $this->chatmsg = $chatmsg;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('live.chat.' . $this->livestream->profile_id);
    }

    public function broadcastAs()
    {
        return 'chat.delete-message';
    }

    public function broadcastWith()
    {
        return ['id' => $this->chatmsg['id']];
    }
}
