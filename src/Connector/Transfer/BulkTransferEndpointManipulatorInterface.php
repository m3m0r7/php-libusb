<?php

declare(strict_types=1);

namespace Libusb\Connector\Transfer;

interface BulkTransferEndpointManipulatorInterface
{
    /**
     * @return BulkTransferEndpointInterface[]
     */
    public function endpoints(): array;

    /**
     * @return BulkTransferEndpointInterface[]
     */
    public function outEndpoints(): array;

    /**
     * @return BulkTransferEndpointInterface[]
     */
    public function inEndpoints(): array;

    public function send(string $payload, int $timeout = BulkTransferEndpointManipulator::DEFAULT_TIMEOUT): BulkTransferEndpointManipulatorInterface;

    /**
     * @return array<int, string>
     */
    public function receive(int $size, int $timeout = BulkTransferEndpointManipulator::DEFAULT_TIMEOUT): array;
}
