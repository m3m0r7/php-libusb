<?php

declare(strict_types=1);

namespace Libusb\Loader;

class StringLoader implements LoaderInterface
{
    public function __construct(protected readonly string $header, protected readonly string $libraryPath) {}

    #[\Override]
    public function header(): string
    {
        return (string) new FFIPreprocessor($this->header);
    }

    #[\Override]
    public function libraryPath(): string
    {
        return $this->libraryPath;
    }
}
