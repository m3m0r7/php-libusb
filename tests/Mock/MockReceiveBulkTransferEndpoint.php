<?php

declare(strict_types=1);

namespace Tests\Libusb\Mock;

use Libusb\Connector\Transfer\ReceiveBulkTransferEndpointInterface;

class MockReceiveBulkTransferEndpoint implements ReceiveBulkTransferEndpointInterface
{
    #[\Override]
    public function number(): int
    {
        return 0;
    }

    #[\Override]
    public function receive(int $size, int $timeout): string
    {
        return str_repeat("\x00", $size);
    }
}
