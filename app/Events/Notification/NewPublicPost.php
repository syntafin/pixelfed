<?php

namespace App\Events\Notification;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Status;
use App\Transformer\Api\StatusTransformer;
use League\Fractal;

class NewPublicPost implements ShouldBroadcastNow
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    protected $status;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function broadcastAs()
    {
        return 'status';
    }

    public function broadcastOn()
    {
        return new Channel('firehost.public');
    }

    public function broadcastWith()
    {
        $resource = new Fractal\Resource\Item($this->status, new StatusTransformer());
        $res = $this->fractal->createData($resource)->toArray();
        return [
            'entity' => $res
        ];
    }

    public function via()
    {
        return 'broadcast';
    }
}
