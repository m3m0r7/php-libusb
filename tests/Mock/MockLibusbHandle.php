<?php

declare(strict_types=1);

namespace Tests\Libusb\Mock;

use Libusb\Device\DeviceInterface;
use Libusb\LibusbHandleInterface;

class MockLibusbHandle implements LibusbHandleInterface
{
    #[\Override]
    public function devices(int $vendorId = null, int $productId = null): array
    {
        $devices = [
            new MockDevice(
                new MockDeviceDescriptor(
                    vendorId: 0x0001,
                    productId: 0x0000,
                    product: 'Mocked USB Device 0',
                    serial: '0000',
                ),
            ),
            new MockDevice(
                new MockDeviceDescriptor(
                    vendorId: 0x0001,
                    productId: 0x0001,
                    product: 'Mocked USB Device 1',
                    serial: '0001',
                ),
            ),
            new MockDevice(
                new MockDeviceDescriptor(
                    vendorId: 0x0001,
                    productId: 0x0002,
                    product: 'Mocked USB Device 2',
                    serial: '0002',
                ),
            ),
        ];

        return array_values(
            array_filter(
                $devices,
                static fn (DeviceInterface $device) => ($vendorId === null || $device->descriptor()->vendorId() === $vendorId)
                    && ($productId === null || $device->descriptor()->productId() === $productId),
            ),
        );
    }
}
