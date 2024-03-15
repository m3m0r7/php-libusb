<?php

declare(strict_types=1);

namespace Tests\Libusb\Mock;

use FFI\CData;
use Libusb\Connector\Transfer\BulkTransferEndpointManipulator;
use Libusb\Connector\Transfer\BulkTransferEndpointManipulatorInterface;
use Libusb\Device\DeviceDescriptorInterface;
use Libusb\Device\DeviceInterface;

class MockDevice implements DeviceInterface
{
    protected ?int $configuration = null;

    protected ?int $interface = null;

    public function __construct(protected MockDeviceDescriptor $deviceDescriptor) {}

    #[\Override]
    public function context(): ?CData
    {
        return null;
    }

    #[\Override]
    public function descriptor(): DeviceDescriptorInterface
    {
        return $this->deviceDescriptor;
    }

    #[\Override]
    public function setClaimInterface(int $interface): DeviceInterface
    {
        $this->interface = $interface;

        return $this;
    }

    #[\Override]
    public function setConfiguration(int $configuration): DeviceInterface
    {
        $this->configuration = $configuration;

        return $this;
    }

    #[\Override]
    public function bulkTransferEndpoints(): BulkTransferEndpointManipulatorInterface
    {
        return new BulkTransferEndpointManipulator([
            new MockSendBulkTransferEndpoint(),
            new MockReceiveBulkTransferEndpoint(),
        ]);
    }
}
