<?php

declare(strict_types=1);

namespace Tests\Libusb\Unit;

use Libusb\Connector\Libusb1_0;
use Libusb\Libusb;
use Libusb\LibusbHandle;
use Libusb\Loader\AutoLoader;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class Libusb1_0Test extends TestCase
{
    public function testInstantiateWithLibusb10Driver(): void
    {
        $this->expectNotToPerformAssertions();

        new Libusb(
            new LibusbHandle(
                new Libusb1_0(
                    new AutoLoader(
                        '1.0',
                        [
                            // for mac
                            ...(
                                is_dir('/opt/homebrew/Cellar/libusb')
                                    ? glob('/opt/homebrew/Cellar/libusb/1.0.*') ?: []
                                    : []
                            ),
                        ]
                    ),
                )
            ),
        );
    }
}
