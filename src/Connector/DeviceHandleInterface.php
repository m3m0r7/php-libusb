<?php

declare(strict_types=1);

namespace Libusb\Connector;

use FFI\CData;

interface DeviceHandleInterface
{
    public function setConfiguration(int $configuration): DeviceHandleInterface;

    public function setClaimInterface(int $interface): DeviceHandleInterface;

    public function context(): CData;
}
