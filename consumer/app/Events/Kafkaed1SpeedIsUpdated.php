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
    public int $speed;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(int $speed)
    {
        $this->speed = $speed;
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
