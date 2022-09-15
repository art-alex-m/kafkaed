<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;

/**
 * Class Kafkaed1SpeedIsUpdated.
 *
 * @package App\Events
 */
class Kafkaed1SpeedIsUpdated implements ShouldBroadcastNow
{
    use Dispatchable;

    /** @var int Message processing speed */
    public readonly int $speed;
    /** @var int Transmission of message generation */
    public readonly int $transmission;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $speed, int $transmission)
    {
        $this->speed = $speed;
        $this->transmission = $transmission;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('kafkaed-1-speed');
    }
}
