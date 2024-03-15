<?php

declare(strict_types=1);

namespace Libusb\Connector\Transfer;

interface ReceiveBulkTransferEndpointInterface extends BulkTransferEndpointInterface
{
    public function receive(int $size, int $timeout): string;
}
