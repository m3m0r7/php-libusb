<?php

declare(strict_types=1);

namespace Tests\Libusb\Mock;

use Libusb\Device\DeviceDescriptorInterface;

class MockDeviceDescriptor implements DeviceDescriptorInterface
{
    public function __construct(protected ?int $vendorId = null, protected ?int $productId = null, protected ?int $busNumber = null, protected ?int $port = null, protected ?string $manufacturer = null, protected ?string $product = null, protected ?string $serial = null) {}

    #[\Override]
    public function vendorId(): ?int
    {
        return $this->vendorId;
    }

    #[\Override]
    public function productId(): ?int
    {
        return $this->productId;
    }

    #[\Override]
    public function busNumber(): ?int
    {
        return $this->busNumber;
    }

    #[\Override]
    public function port(): ?int
    {
        return $this->port;
    }

    #[\Override]
    public function manufacturer(): ?string
    {
        return $this->manufacturer;
    }

    #[\Override]
    public function product(): ?string
    {
        return $this->product;
    }

    #[\Override]
    public function serialNumber(): ?string
    {
        return $this->serial;
    }
}
