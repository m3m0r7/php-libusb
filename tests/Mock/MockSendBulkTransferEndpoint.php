<?php

declare(strict_types=1);

namespace Tests\Libusb\Mock;

use Libusb\Connector\Transfer\SendBulkTransferEndpointInterface;

class MockSendBulkTransferEndpoint implements SendBulkTransferEndpointInterface
{
    #[\Override]
    public function number(): int
    {
        return 0;
    }

    #[\Override]
    public function send(string $payload, int $timeout): void {}
}
