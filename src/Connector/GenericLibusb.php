<?php

declare(strict_types=1);

namespace Libusb\Connector;

use FFI\CData;

trait GenericLibusb
{
    public function itoa(CData $UIntArray): string
    {
        $size = \FFI::sizeof($UIntArray);

        $string = [];
        for ($i = 0; $i < $size; ++$i) {
            $string[$i] = chr($UIntArray[$i]);
        }

        return rtrim(implode('', $string));
    }

    public function atoi(string $string, int $size = null): CData
    {
        $size = ($size === null ? strlen($string) : $size) + 1;
        $uInt8Array = $this->new("uint8_t[{$size}]");
        for ($i = 0; $i < $size; ++$i) {
            $uInt8Array[$i] = isset($string[$i])
                ? ord($string[$i])
                : 0;
        }

        return $uInt8Array;
    }
}
