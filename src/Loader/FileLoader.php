<?php

declare(strict_types=1);

namespace Libusb\Loader;

class FileLoader implements LoaderInterface
{
    protected ?string $header = null;

    public function __construct(protected readonly string $path, protected readonly string $libraryPath) {}

    /**
     * @return array{path: string, libraryPath: string}|null
     */
    public function __debugInfo(): ?array
    {
        return [
            'path' => $this->path,
            'libraryPath' => $this->libraryPath,
        ];
    }

    #[\Override]
    public function header(): string
    {
        return (string) new FFIPreprocessor($this->header ??= file_get_contents($this->path) ?: '');
    }

    #[\Override]
    public function libraryPath(): string
    {
        return $this->libraryPath;
    }
}
