<?php

declare(strict_types=1);

namespace Libusb\Device;

use FFI\CData;
use Libusb\Connector\Transfer\BulkTransferEndpointManipulatorInterface;

interface DeviceInterface
{
    public function context(): ?CData;

    public function descriptor(): DeviceDescriptorInterface;

    public function bulkTransferEndpoints(): BulkTransferEndpointManipulatorInterface;

    public function setClaimInterface(int $interface): DeviceInterface;

    public function setConfiguration(int $configuration): DeviceInterface;
}
