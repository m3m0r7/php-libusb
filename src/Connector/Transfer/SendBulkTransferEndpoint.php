<?php

declare(strict_types=1);

namespace Libusb\Connector\Transfer;

use Libusb\Connector\LibusbInterface;
use Libusb\Exception\SendBulkTransferException;
use FFI\CData;

class SendBulkTransferEndpoint implements SendBulkTransferEndpointInterface
{
    public function __construct(protected LibusbInterface $libusb, protected CData $context, protected int $endpoint) {}

    #[\Override]
    public function send(string $payload, int $timeout): void
    {
        $length = $this
            ->libusb
            ->new('int');

        $errorCode = $this
            ->libusb
            ->libusb_bulk_transfer(
                $this->context,
                $this->number(),
                $this->libusb->atoi($payload),
                strlen($payload),
                \FFI::addr($length),
                $timeout,
            );

        if ($errorCode < 0) {
            throw new SendBulkTransferException(
                sprintf(
                    'Failed to send bulk transfer %d',
                    $errorCode,
                ),
                $errorCode,
            );
        }
    }

    #[\Override]
    public function number(): int
    {
        return $this->endpoint;
    }
}
