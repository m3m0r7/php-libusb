<?php

declare(strict_types=1);

namespace Libusb;

use Libusb\Device\DeviceInterface;

interface LibusbHandleInterface
{
    /**
     * @return DeviceInterface[]
     */
    public function devices(int $vendorId = null, int $productId = null): array;
}
