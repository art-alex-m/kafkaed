<?php

namespace Artalexm\KafkaedCommon\Messages;

use Illuminate\Support\Str;
use Ramsey\Uuid\UuidInterface;

/**
 * Class Transmission.
 *
 * @package Artalexm\KafkaedCommon\Messages
 */
class Transmission
{
    public readonly UuidInterface $id;
    public readonly string $class;
    public readonly float $created;
    public readonly int $transmission;

    /**
     * @param int $created
     * @param int $transmission
     */
    public function __construct(float $created, int $transmission)
    {
        $this->created = $created;
        $this->transmission = $transmission;
        $this->class = static::class;
        $this->id = Str::uuid();
    }
}
