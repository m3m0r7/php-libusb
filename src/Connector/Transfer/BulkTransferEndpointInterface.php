<?php

declare(strict_types=1);

namespace Libusb\Connector\Transfer;

interface BulkTransferEndpointInterface
{
    public function number(): int;
}
