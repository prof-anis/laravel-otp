<?php

namespace Tobexkee\LaravelOtp\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Tobexkee\LaravelOtp\Models\LaravelOtp;

class OtpGeneratedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private LaravelOtp $otp;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(LaravelOtp $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
