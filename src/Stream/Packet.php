<?php

declare(strict_types=1);

namespace Libusb\Stream;

class Packet implements \Stringable
{
    /**
     * @param int[] $packets
     */
    public function __construct(protected array $packets) {}

    #[\Override]
    public function __toString(): string
    {
        return pack('C*', ...$this->packets);
    }
}
