<?php

declare(strict_types=1);

namespace Libusb\Loader;

use Libusb\Exception\LibusbException;
use Libusb\Exception\NotFoundLibusbHeaderFile;
use Libusb\Exception\NotFoundLibusbLibraryFile;

class AutoLoader implements LoaderInterface
{
    protected ?string $header = null;

    protected ?string $libraryPath = null;

    /**
     * @param string[] $loaderPaths
     */
    public function __construct(protected string $version, protected array $loaderPaths)
    {
        foreach ($this->loaderPaths as $loaderPath) {
            $this->header = $this->first("{$loaderPath}/include/*/libusb.h");
            $this->libraryPath = $this->first("{$loaderPath}/lib/libusb*.dylib") ?? $this->first("{$loaderPath}/lib/libusb*.so");

            if ($this->header && $this->libraryPath) {
                break;
            }
        }
    }

    #[\Override]
    public function header(): string
    {
        return (string) new FFIPreprocessor(
            file_get_contents(
                $this->header ?? throw new NotFoundLibusbHeaderFile(
                    'Not found libusb header file. Did you forget to install libusb via package manager?',
                ),
            ) ?: throw new LibusbException(
                sprintf(
                    'Cannot read FFI header file %s',
                    $this->header,
                ),
            ),
        );
    }

    #[\Override]
    public function libraryPath(): string
    {
        return $this->libraryPath ?? throw new NotFoundLibusbLibraryFile(
            'Not found libusb library file. Did you forget to install libusb via package manager?',
        );
    }

    private function first(string $path): ?string
    {
        $result = glob($path);
        if ($result === []) {
            return null;
        }

        return $result[0] ?? null;
    }
}
