<?php

declare(strict_types=1);

namespace Libusb\Device;

interface DeviceDescriptorInterface
{
    public function vendorId(): ?int;

    public function productId(): ?int;

    public function busNumber(): ?int;

    public function port(): ?int;

    public function manufacturer(): ?string;

    public function product(): ?string;

    public function serialNumber(): ?string;
}
