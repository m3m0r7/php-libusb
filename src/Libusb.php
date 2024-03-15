<?php

declare(strict_types=1);

namespace Libusb;

use Libusb\Device\DeviceInterface;

class Libusb
{
    public function __construct(protected LibusbHandleInterface $libusbHandle) {}

    /**
     * @return DeviceInterface[]
     */
    public function devices(int $vendorId = null, int $productId = null): array
    {
        return $this->libusbHandle->devices($vendorId, $productId);
    }
}
