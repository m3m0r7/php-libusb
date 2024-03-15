<?php

declare(strict_types=1);

namespace Libusb\Connector\Transfer;

class BulkTransferEndpointManipulator implements BulkTransferEndpointManipulatorInterface
{
    public const DEFAULT_TIMEOUT = 10;

    /**
     * @param BulkTransferEndpointInterface[] $endpoints
     */
    public function __construct(protected array $endpoints) {}

    #[\Override]
    public function endpoints(): array
    {
        return $this->endpoints;
    }

    /**
     * @return SendBulkTransferEndpointInterface[]
     */
    #[\Override]
    public function outEndpoints(): array
    {
        return array_values(array_filter($this->endpoints(), static fn (BulkTransferEndpointInterface $bulkTransfer) => $bulkTransfer instanceof SendBulkTransferEndpointInterface));
    }

    /**
     * @return ReceiveBulkTransferEndpointInterface[]
     */
    #[\Override]
    public function inEndpoints(): array
    {
        return array_values(array_filter($this->endpoints(), static fn (BulkTransferEndpointInterface $bulkTransfer) => $bulkTransfer instanceof ReceiveBulkTransferEndpointInterface));
    }

    #[\Override]
    public function send(string $payload, int $timeout = BulkTransferEndpointManipulator::DEFAULT_TIMEOUT): BulkTransferEndpointManipulatorInterface
    {
        foreach ($this->outEndpoints() as $endpoint) {
            $endpoint->send($payload, $timeout);
        }

        return $this;
    }

    #[\Override]
    public function receive(int $size, int $timeout = BulkTransferEndpointManipulator::DEFAULT_TIMEOUT): array
    {
        $receives = [];

        foreach ($this->inEndpoints() as $endpoint) {
            $receives[$endpoint->number()] = $endpoint->receive($size, $timeout);
        }

        return $receives;
    }
}
