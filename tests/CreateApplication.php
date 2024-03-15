<?php

declare(strict_types=1);

namespace Tests\Libusb;

use Libusb\Libusb;
use Tests\Libusb\Mock\MockLibusbHandle;

trait CreateApplication
{
    protected Libusb $libusb;

    public function setUp(): void
    {
        parent::setUp();

        $this->libusb = new Libusb(
            new MockLibusbHandle(),
        );
    }
}
