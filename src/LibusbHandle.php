<?php

declare(strict_types=1);

namespace Libusb;

use Libusb\Connector\LibusbInterface;
use Libusb\Device\DeviceInterface;

class LibusbHandle implements LibusbHandleInterface
{
    public function __construct(protected LibusbInterface $libusb) {}

    #[\Override]
    public function devices(int $vendorId = null, int $productId = null): array
    {
        return array_values(
            array_filter(
                $this->libusb->devices(),
                static fn (DeviceInterface $device) => ($vendorId === null || $device->descriptor()->vendorId() === $vendorId)
                    && ($productId === null || $device->descriptor()->productId() === $productId),
            ),
        );
    }
}
