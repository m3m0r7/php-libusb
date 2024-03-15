<?php

declare(strict_types=1);

namespace Libusb\Device;

use Libusb\Connector\DeviceHandleInterface;
use Libusb\Connector\LibusbInterface;
use FFI\CData;
use Libusb\Connector\Transfer\BulkTransferEndpointManipulator;
use Libusb\Connector\Transfer\BulkTransferEndpointManipulatorInterface;

class Device implements DeviceInterface
{
    private BulkTransferEndpointManipulatorInterface $bulkTransferEndpointsManipulator;

    public function __construct(
        protected LibusbInterface $libusb,
        protected CData $context,
        protected DeviceHandleInterface $deviceHandle,
        protected DeviceDescriptorInterface $deviceDescriptor,
    ) {}

    #[\Override]
    public function descriptor(): DeviceDescriptorInterface
    {
        return $this->deviceDescriptor;
    }

    #[\Override]
    public function setConfiguration(int $configuration): DeviceInterface
    {
        $this->deviceHandle->setConfiguration($configuration);

        return $this;
    }

    #[\Override]
    public function setClaimInterface(int $interface): DeviceInterface
    {
        $this->deviceHandle->setClaimInterface($interface);

        return $this;
    }

    #[\Override]
    public function context(): ?CData
    {
        return $this->context;
    }

    #[\Override]
    public function bulkTransferEndpoints(): BulkTransferEndpointManipulatorInterface
    {
        return $this->bulkTransferEndpointsManipulator ??= new BulkTransferEndpointManipulator($this->libusb
            ->bulkTransportEndpoints(
                $this->context,
                $this->deviceHandle->context(),
            ));
    }
}
