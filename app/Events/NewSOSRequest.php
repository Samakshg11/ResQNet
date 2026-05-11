<?php

namespace App\Events;

use App\Models\SOSRequest;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewSOSRequest implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sos;

    public function __construct(SOSRequest $sos)
    {
        $this->sos = $sos;
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('emergency-channel'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'new.sos';
    }
}
