<?php

declare(strict_types=1);

namespace Libusb\Connector\Transfer;

use Libusb\Connector\LibusbInterface;
use Libusb\Exception\ReceiveBulkTransferException;
use FFI\CData;

class ReceiveBulkTransferEndpoint implements ReceiveBulkTransferEndpointInterface
{
    public function __construct(protected LibusbInterface $libusb, protected CData $context, protected int $endpoint) {}

    #[\Override]
    public function receive(int $size, int $timeout): string
    {
        $received = $this
            ->libusb
            ->new('uint8_t[' . $size . ']');

        $length = $this
            ->libusb
            ->new('int');

        $errorCode = $this
            ->libusb
            ->libusb_bulk_transfer(
                $this->context,
                $this->endpoint,
                $received,
                \FFI::sizeof($received),
                \FFI::addr($length),
                $timeout,
            );

        if ($errorCode < 0) {
            throw new ReceiveBulkTransferException(
                sprintf(
                    'Failed to receive bulk transfer %d',
                    $errorCode,
                ),
                $errorCode,
            );
        }

        return $this->libusb->itoa($received);
    }

    #[\Override]
    public function number(): int
    {
        return $this->endpoint;
    }
}
