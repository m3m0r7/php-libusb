<?php

declare(strict_types=1);

namespace Tests\Libusb\Unit;

use Libusb\Connector\Transfer\BulkTransferEndpointInterface;
use Libusb\Stream\Packet;
use PHPUnit\Framework\TestCase;
use Tests\Libusb\CreateApplication;
use Tests\Libusb\Mock\MockReceiveBulkTransferEndpoint;
use Tests\Libusb\Mock\MockSendBulkTransferEndpoint;

/**
 * @internal
 *
 * @coversNothing
 */
class LibusbTest extends TestCase
{
    use CreateApplication;

    public function testListDevices(): void
    {
        $devices = $this->libusb->devices();

        $this->assertSame(3, count($devices));
        $this->assertSame('Mocked USB Device 0', $devices[0]->descriptor()->product());
        $this->assertSame('0000', $devices[0]->descriptor()->serialNumber());

        $this->assertSame('Mocked USB Device 1', $devices[1]->descriptor()->product());
        $this->assertSame('0001', $devices[1]->descriptor()->serialNumber());

        $this->assertSame('Mocked USB Device 2', $devices[2]->descriptor()->product());
        $this->assertSame('0002', $devices[2]->descriptor()->serialNumber());
    }

    public function testBulkTransferEndpointManipulator(): void
    {
        [$device] = $this->libusb->devices();

        $device->setConfiguration(0)
            ->setClaimInterface(0);

        $this->assertContainsOnlyInstancesOf(
            BulkTransferEndpointInterface::class,
            $device->bulkTransferEndpoints()->endpoints(),
        );

        $this->assertContainsOnlyInstancesOf(
            MockSendBulkTransferEndpoint::class,
            $device->bulkTransferEndpoints()->outEndpoints(),
        );

        $this->assertContainsOnlyInstancesOf(
            MockReceiveBulkTransferEndpoint::class,
            $device->bulkTransferEndpoints()->inEndpoints(),
        );
    }

    public function testSend(): void
    {
        $this->expectNotToPerformAssertions();

        [$device] = $this->libusb->devices();

        $device->setConfiguration(0)
            ->setClaimInterface(0);

        $device->bulkTransferEndpoints()->send(
            (string) new Packet([0x01, 0x02, 0x03, 0x04]),
        );
    }

    public function testReceive(): void
    {
        [$device] = $this->libusb->devices();

        $device->setConfiguration(0)
            ->setClaimInterface(0);

        [$received] = $device->bulkTransferEndpoints()->receive(100);
        $this->assertSame(100, strlen((string) $received));
    }
}
