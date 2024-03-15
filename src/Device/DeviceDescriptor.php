<?php

declare(strict_types=1);

namespace Libusb\Device;

class DeviceDescriptor implements DeviceDescriptorInterface
{
    public function __construct(
        protected ?int $vendorId,
        protected ?int $productId,
        protected ?int $busNumber,
        protected ?int $port,
        protected ?string $iManufacturer,
        protected ?string $iProduct,
        protected ?string $iSerialNumber,
    ) {}

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
        return $this->vendorId;
    }

    #[\Override]
    public function port(): ?int
    {
        return $this->productId;
    }

    #[\Override]
    public function manufacturer(): ?string
    {
        return $this->iManufacturer;
    }

    #[\Override]
    public function product(): ?string
    {
        return $this->iProduct;
    }

    #[\Override]
    public function serialNumber(): ?string
    {
        return $this->iSerialNumber;
    }
}
