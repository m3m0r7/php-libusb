<?php

declare(strict_types=1);

namespace Libusb\Connector;

use FFI\CData;

class DeviceHandle implements DeviceHandleInterface
{
    public function __construct(protected LibusbInterface $libusb, protected CData $context) {}

    #[\Override]
    public function context(): CData
    {
        return $this->context;
    }

    #[\Override]
    public function setConfiguration(int $configuration): DeviceHandleInterface
    {
        $this->libusb->setConfiguration($this->context, $configuration);

        return $this;
    }

    #[\Override]
    public function setClaimInterface(int $interface): DeviceHandleInterface
    {
        $this->libusb->setClaimInterface($this->context, $interface);

        return $this;
    }
}
