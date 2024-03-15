<?php

declare(strict_types=1);

namespace Libusb\Loader;

interface LoaderInterface
{
    public function header(): string;

    public function libraryPath(): string;
}
