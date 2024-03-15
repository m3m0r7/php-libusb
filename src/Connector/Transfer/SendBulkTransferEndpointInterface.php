<?php

declare(strict_types=1);

namespace Libusb\Connector\Transfer;

interface SendBulkTransferEndpointInterface extends BulkTransferEndpointInterface
{
    public function send(string $payload, int $timeout): void;
}
