<?php

declare(strict_types=1);

namespace Libusb\Connector;

use Libusb\Connector\Transfer\BulkTransferEndpointInterface;
use Libusb\Device\DeviceInterface;
use Libusb\Loader\LoaderInterface;
use FFI\CData;

interface LibusbInterface
{
    public function __construct(LoaderInterface $loader);

    /**
     * @return DeviceInterface[]
     */
    public function devices(): array;

    public function getErrorByCode(int $errorCode): string;

    /**
     * @return BulkTransferEndpointInterface[]
     */
    public function bulkTransportEndpoints(CData $context, CData $handleContext): array;

    public function setConfiguration(CData $context, int $configuration): void;

    public function setClaimInterface(CData $context, int $interface): void;
}
