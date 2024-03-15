# php-libusb

## What is this?
This project is wrapping the libusb with PHP. The libusb is written in C and bridge to it via PHP FFI.
*This project is experimental project*.

## Requirements
- PHP 8.3+
- libusb

## Prepare

### macOS

1. Install libusb via brew if you use macOS

```
$ brew install libusb
```

2. You will get dynamic library is here:

```
/opt/homebrew/Cellar/libusb/<your libusb version>/lib/libusb-<your libusb version>.dylib
```

3. You will get header file is here:

```
/opt/homebrew/Cellar/libusb/<your libusb version>/include/libusb-<your libusb version>/libusb.h
```

### Windows
TBD

### Ubuntu
TBD

## Get started

### Quick start

1. Install this library
```
$ composer require m3m0r7/php-libusb
```


2. Write below code quick start code and save as `test.php`
```php
<?php

require __DIR__ . '/vendor/autoload.php';

$libusb = new \Libusb\Libusb(
    new \Libusb\LibusbHandle(
        new \Libusb\Connector\Libusb1_0(
            new \Libusb\Loader\FileLoader(
                // Set libusb header file here:
                '/path/to/libusb.h',

                // Set libusb library
                // Replace "so" to "dylib" if you use macOS:
                '/path/to/libusb-1.0.so',
            ),
        )
    ),
);

/**
 * @var \Libusb\Device\DeviceInterface $device
 */
foreach ($libusb->devices() as $device) {
    printf(
        "%s (serial: %s)\n",
        $device->descriptor()->product(),
        $device->descriptor()->serialNumber(),
    );
}
```

3. Run `php test.php` to be got devices list

```
$ php test.php

USB Single Serial (serial: XXX)
YubiKey OTP+FIDO+CCID (serial: XXX)
USB2.0 Hub (serial: XXX)
USB3.1 Hub (serial: XXX)
4-Port USB 2.0 Hub (serial: XXX)
Logitech StreamCam (serial: XXX)
```


### Send/Receive packet from serial port

1. Write below code and save as `test2.php`

```php
<?php

require __DIR__ . '/vendor/autoload.php';

$libusb = new \Libusb\Libusb(
    new \Libusb\LibusbHandle(
        new \Libusb\Connector\Libusb1_0(
            new \Libusb\Loader\FileLoader(
                // Set libusb header file here:
                '/path/to/libusb.h',

                // Set libusb library
                // Replace "so" to "dylib" if you use macOS:
                '/path/to/libusb-1.0.so',
            ),
        )
    ),
);

/**
* @var \Libusb\Device\DeviceInterface $device
*/
[$device] = $libusb->devices(
    // Specify vendor id is here
    0x0101,

    // Specify product id is here
    0xabab,
);

$device
    // Set configuration
    ->setConfiguration(1)
    // Set claim interface
    ->setClaimInterface(0);

// Create bulk transfer endpoint
$bulkTransfer = $device->bulkTransferEndpoints();

// Send packet
$bulkTransfer
    ->send(
        (string) new \Libusb\Stream\Packet([0x01, 0x02, 0x03, 0x04]),
    );

// Received packet
$received = $bulkTransfer
    ->receive();

```


2. Run `php test2.php` then sent packets to the device and received from it.

## How to test

```
./vendor/bin/phpunit
```

## LICENSE
MIT

