<?php

declare(strict_types=1);

namespace Libusb\Loader;

use Libusb\Exception\LibusbException;
use Libusb\Exception\NotFoundCProcessor;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class FFIPreprocessor implements \Stringable
{
    public function __construct(protected string $header)
    {
        if (getenv('PHPFFI_LIBUSB_NO_CACHE')) {
            $this->removePreprocessedFFIHeaderFile();
        }

        $this->createFFIableHeaderFile();
    }

    protected function removePreprocessedFFIHeaderFile(): void
    {
        @unlink($this->path());
    }

    protected function path(): string
    {
        return sys_get_temp_dir() . '/libusb-ffi.h';
    }

    protected function createFFIableHeaderFile(): void
    {
        if (is_file($this->path())) {
            return;
        }

        $tmpfile = tmpfile() ?: throw new LibusbException('failed to create tmpfile for normalizing FFI');
        fwrite($tmpfile, $this->normalizeHeader());
        $this->preprocessCDirective(
            stream_get_meta_data($tmpfile)['uri'],
        );
    }

    protected function normalizeHeader(): string
    {
        return $this->addTypeDefinitions(
            $this->removeInlineCodes(
                $this->removeIncludes(
                    $this->header,
                ),
            ),
        );
    }

    protected function preprocessCDirective(string $headerPourcePath): void
    {
        // TODO: Remove external process calling.
        if (getenv('PHPFFI_LIBUSB_NO_C_PREPROCESS')) {
            @copy($headerPourcePath, $this->path());

            return;
        }

        $command = 'cpp';

        $commandAvailableProcess = new Process(['which', $command]);
        $commandAvailableProcess->run();

        if (!$commandAvailableProcess->isSuccessful()) {
            throw new NotFoundCProcessor(
                sprintf(
                    'Not found %s command. If you want to disable preprocess C directive. ' .
                    'then adding PHPFFI_LIBUSB_NO_C_PREPROCESS=1 when running, or did you forget install %s command on your environment if not working.',
                    $command,
                    $command,
                ),
            );
        }

        $process = new Process([
            $command,
            '-P',
            '-C',
            '-D __attribute__(ARGS)= ',
            $headerPourcePath,
        ]);
        $process->enableOutput();
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        file_put_contents(
            $this->path(),
            $process->getOutput(),
            LOCK_EX,
        );
    }

    #[\Override]
    public function __toString(): string
    {
        return (string) file_get_contents($this->path());
    }

    protected function addTypeDefinitions(string $header): string
    {
        $typeDefinitions = [
            'libusb_device_descriptor',
            'libusb_config_descriptor',
        ];

        $header .= "\n";
        foreach ($typeDefinitions as $typeDefinition) {
            $header .= sprintf("typedef struct %s %s;\n", $typeDefinition, $typeDefinition);
        }

        return $header;
    }

    protected function removeInlineCodes(string $header): string
    {
        $header = preg_replace("/(\\(|,)\n/", '$1', $header);

        return (string) preg_replace("/(static inline [^\n]+)\n{.+?\n}/s", '$1;', (string) $header);
    }

    protected function removeIncludes(string $header): string
    {
        return (string) preg_replace("/\\#include[^\n]+/", '', $header);
    }
}
