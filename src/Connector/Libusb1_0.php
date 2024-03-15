<?php

declare(strict_types=1);

namespace Libusb\Connector;

use FFI\CData;
use Libusb\Connector\Transfer\ReceiveBulkTransferEndpoint;
use Libusb\Connector\Transfer\SendBulkTransferEndpoint;
use Libusb\Device\Device;
use Libusb\Device\DeviceDescriptor;
use Libusb\Device\DeviceDescriptorInterface;
use Libusb\Exception\CannotClaimInterface;
use Libusb\Exception\CannotRetrieveConfigDescriptor;
use Libusb\Exception\CannotRetrieveDeviceDescriptor;
use Libusb\Exception\CannotSetConfiguration;
use Libusb\Loader\LoaderInterface;

/**
 * @method int             libusb_init(null|\FFI\CData $ctx)
 * @method void            libusb_exit(null|\FFI\CData $ctx)
 * @method void            libusb_set_debug(null|\FFI\CData $ctx, int $level)
 * @method null|\FFI\CData libusb_get_version()
 * @method int             libusb_has_capability(null|\FFI\CData $capability)
 * @method null|\FFI\CData libusb_error_name(int $errcode)
 * @method int             libusb_setlocale(string $locale)
 * @method null|\FFI\CData libusb_strerror(int $errcode)
 * @method int             libusb_get_device_list(null|\FFI\CData $ctx, null|\FFI\CData $list)
 * @method void            libusb_free_device_list(null|\FFI\CData $list, int $unref_devices)
 * @method null|\FFI\CData libusb_ref_device(null|\FFI\CData $dev)
 * @method void            libusb_unref_device(null|\FFI\CData $dev)
 * @method int             libusb_get_configuration(null|\FFI\CData $dev, null|\FFI\CData $config)
 * @method int             libusb_get_device_descriptor(null|\FFI\CData $dev, null|\FFI\CData $desc)
 * @method int             libusb_get_active_config_descriptor(null|\FFI\CData $dev, null|\FFI\CData $config)
 * @method int             libusb_get_config_descriptor(null|\FFI\CData $dev, int $config_index, null|\FFI\CData $config)
 * @method int             libusb_get_config_descriptor_by_value(null|\FFI\CData $dev, int $bConfigurationValue, null|\FFI\CData $config)
 * @method void            libusb_free_config_descriptor(null|\FFI\CData $config)
 * @method int             libusb_get_ss_endpoint_companion_descriptor(null|\FFI\CData $ctx, null|\FFI\CData $endpoint, null|\FFI\CData $ep_comp)
 * @method void            libusb_free_ss_endpoint_companion_descriptor(null|\FFI\CData $ep_comp)
 * @method int             libusb_get_bos_descriptor(null|\FFI\CData $dev_handle, null|\FFI\CData $bos)
 * @method void            libusb_free_bos_descriptor(null|\FFI\CData $bos)
 * @method int             libusb_get_usb_2_0_extension_descriptor(null|\FFI\CData $ctx, null|\FFI\CData $dev_cap, null|\FFI\CData $usb_2_0_extension)
 * @method void            libusb_free_usb_2_0_extension_descriptor(null|\FFI\CData $usb_2_0_extension)
 * @method int             libusb_get_ss_usb_device_capability_descriptor(null|\FFI\CData $ctx, null|\FFI\CData $dev_cap, null|\FFI\CData $ss_usb_device_cap)
 * @method void            libusb_free_ss_usb_device_capability_descriptor(null|\FFI\CData $ss_usb_device_cap)
 * @method int             libusb_get_container_id_descriptor(null|\FFI\CData $ctx, null|\FFI\CData $dev_cap, null|\FFI\CData $container_id)
 * @method void            libusb_free_container_id_descriptor(null|\FFI\CData $container_id)
 * @method int             libusb_get_bus_number(null|\FFI\CData $dev)
 * @method int             libusb_get_port_number(null|\FFI\CData $dev)
 * @method int             libusb_get_port_numbers(null|\FFI\CData $dev, null|\FFI\CData $port_numbers, int $port_numbers_len)
 * @method int             libusb_get_port_path(null|\FFI\CData $ctx, null|\FFI\CData $dev, null|\FFI\CData $path, int $path_length)
 * @method null|\FFI\CData libusb_get_parent(null|\FFI\CData $dev)
 * @method int             libusb_get_device_address(null|\FFI\CData $dev)
 * @method int             libusb_get_device_speed(null|\FFI\CData $dev)
 * @method int             libusb_get_max_packet_size(null|\FFI\CData $dev, string $endpoint)
 * @method int             libusb_get_max_iso_packet_size(null|\FFI\CData $dev, string $endpoint)
 * @method int             libusb_wrap_sys_device(null|\FFI\CData $ctx, null|\FFI\CData $sys_dev, null|\FFI\CData $dev_handle)
 * @method int             libusb_open(null|\FFI\CData $dev, null|\FFI\CData $dev_handle)
 * @method void            libusb_close(null|\FFI\CData $dev_handle)
 * @method null|\FFI\CData libusb_get_device(null|\FFI\CData $dev_handle)
 * @method int             libusb_set_configuration(null|\FFI\CData $dev_handle, int $configuration)
 * @method int             libusb_claim_interface(null|\FFI\CData $dev_handle, int $interface_number)
 * @method int             libusb_release_interface(null|\FFI\CData $dev_handle, int $interface_number)
 * @method null|\FFI\CData libusb_open_device_with_vid_pid(null|\FFI\CData $ctx, null|\FFI\CData $vendor_id, null|\FFI\CData $product_id)
 * @method int             libusb_set_interface_alt_setting(null|\FFI\CData $dev_handle, int $interface_number, int $alternate_setting)
 * @method int             libusb_clear_halt(null|\FFI\CData $dev_handle, string $endpoint)
 * @method int             libusb_reset_device(null|\FFI\CData $dev_handle)
 * @method int             libusb_alloc_streams(null|\FFI\CData $dev_handle, null|\FFI\CData $num_streams, string $endpoints, int $num_endpoints)
 * @method int             libusb_free_streams(null|\FFI\CData $dev_handle, string $endpoints, int $num_endpoints)
 * @method int             libusb_dev_mem_alloc(null|\FFI\CData $dev_handle, int $length)
 * @method int             libusb_dev_mem_free(null|\FFI\CData $dev_handle, string $buffer, int $length)
 * @method int             libusb_kernel_driver_active(null|\FFI\CData $dev_handle, int $interface_number)
 * @method int             libusb_detach_kernel_driver(null|\FFI\CData $dev_handle, int $interface_number)
 * @method int             libusb_attach_kernel_driver(null|\FFI\CData $dev_handle, int $interface_number)
 * @method int             libusb_set_auto_detach_kernel_driver(null|\FFI\CData $dev_handle, int $enable)
 * @method null|\FFI\CData libusb_alloc_transfer(int $iso_packets)
 * @method int             libusb_submit_transfer(null|\FFI\CData $transfer)
 * @method int             libusb_cancel_transfer(null|\FFI\CData $transfer)
 * @method void            libusb_free_transfer(null|\FFI\CData $transfer)
 * @method void            libusb_transfer_set_stream_id(null|\FFI\CData $transfer, null|\FFI\CData $stream_id)
 * @method int             libusb_transfer_get_stream_id(null|\FFI\CData $transfer)
 * @method int             libusb_control_transfer(null|\FFI\CData $dev_handle, int $request_type, int $bRequest, null|\FFI\CData $wValue, null|\FFI\CData $wIndex, null|\FFI\CData $data, null|\FFI\CData $wLength, int $timeout)
 * @method int             libusb_bulk_transfer(null|\FFI\CData $dev_handle, int $endpoint, null|\FFI\CData $data, int $length, null|\FFI\CData $actual_length, int $timeout)
 * @method int             libusb_interrupt_transfer(null|\FFI\CData $dev_handle, string $endpoint, null|\FFI\CData $data, int $length, null|\FFI\CData $actual_length, int $timeout)
 * @method int             libusb_get_string_descriptor_ascii(null|\FFI\CData $dev_handle, int $desc_index, null|\FFI\CData $data, int $length)
 * @method int             libusb_try_lock_events(null|\FFI\CData $ctx)
 * @method void            libusb_lock_events(null|\FFI\CData $ctx)
 * @method void            libusb_unlock_events(null|\FFI\CData $ctx)
 * @method int             libusb_event_handling_ok(null|\FFI\CData $ctx)
 * @method int             libusb_event_handler_active(null|\FFI\CData $ctx)
 * @method void            libusb_interrupt_event_handler(null|\FFI\CData $ctx)
 * @method void            libusb_lock_event_waiters(null|\FFI\CData $ctx)
 * @method void            libusb_unlock_event_waiters(null|\FFI\CData $ctx)
 * @method int             libusb_wait_for_event(null|\FFI\CData $ctx, null|\FFI\CData $tv)
 * @method int             libusb_handle_events_timeout(null|\FFI\CData $ctx, null|\FFI\CData $tv)
 * @method int             libusb_handle_events_timeout_completed(null|\FFI\CData $ctx, null|\FFI\CData $tv, null|\FFI\CData $completed)
 * @method int             libusb_handle_events(null|\FFI\CData $ctx)
 * @method int             libusb_handle_events_completed(null|\FFI\CData $ctx, null|\FFI\CData $completed)
 * @method int             libusb_handle_events_locked(null|\FFI\CData $ctx, null|\FFI\CData $tv)
 * @method int             libusb_pollfds_handle_timeouts(null|\FFI\CData $ctx)
 * @method int             libusb_get_next_timeout(null|\FFI\CData $ctx, null|\FFI\CData $tv)
 * @method void            libusb_free_pollfds(null|\FFI\CData $pollfds)
 * @method void            libusb_set_pollfd_notifiers(null|\FFI\CData $ctx, null|\FFI\CData $added_cb, null|\FFI\CData $removed_cb, null|\FFI\CData $user_data)
 * @method void            libusb_hotplug_deregister_callback(null|\FFI\CData $ctx, null|\FFI\CData $callback_handle)
 * @method void            libusb_hotplug_get_user_data(null|\FFI\CData $ctx, null|\FFI\CData $callback_handle)
 * @method int             libusb_set_option(\FFI\CData|null $ctx, \FFI\CData|null $option, ...$__VAARGS__)
 */
class Libusb1_0 implements Libusb1_0Interface, LibusbInterface
{
    use GenericLibusb;

    public const LIBUSB_ISO_USAGE_TYPE_MASK = 0x30;

    public const LIBUSB_ISO_SYNC_TYPE_MASK = 0x0C;

    public const LIBUSB_TRANSFER_TYPE_MASK = 0x03; // in bmAttributes

    public const LIBUSB_ENDPOINT_ADDRESS_MASK = 0x0F;

    // in bEndpointAddress
    public const LIBUSB_ENDPOINT_DIR_MASK = 0x80;

    public const LIBUSB_DT_DEVICE_SIZE = 18;

    public const LIBUSB_DT_CONFIG_SIZE = 9;

    public const LIBUSB_DT_INTERFACE_SIZE = 9;

    public const LIBUSB_DT_ENDPOINT_SIZE = 7;

    public const LIBUSB_DT_ENDPOINT_AUDIO_SIZE = 9;

    // Audio extension
    public const LIBUSB_DT_HUB_NONVAR_SIZE = 7;

    public const LIBUSB_DT_SS_ENDPOINT_COMPANION_SIZE = 6;

    public const LIBUSB_DT_BOS_SIZE = 5;

    public const LIBUSB_DT_DEVICE_CAPABILITY_SIZE = 3;

    // BOS descriptor sizes
    public const LIBUSB_BT_USB_2_0_EXTENSION_SIZE = 7;

    public const LIBUSB_BT_SS_USB_DEVICE_CAPABILITY_SIZE = 10;

    public const LIBUSB_BT_CONTAINER_ID_SIZE = 20;

    public const LIBUSB_API_VERSION = 0x01000108;

    // The following is kept for compatibility, but will be deprecated in the future
    public const LIBUSBX_API_VERSION = self::LIBUSB_API_VERSION;

    public const ZERO_SIZED_ARRAY = 0; // [0] - non-standard, but usually working code

    protected \FFI $ffi;

    /**
     * @var \FFI\CData[]
     */
    protected array $opendLibusbContexts = [];

    /**
     * @var array{0: \FFI\CData, 1: int}[]
     */
    protected array $claimedInterfaces = [];

    public function __construct(protected LoaderInterface $loader)
    {
        $this->ffi = \FFI::cdef(
            $loader->header(),
            $this->loader->libraryPath(),
        );

        // Initialize
        $this->libusb_init(null);
    }

    protected function releaseInterfaces(): void
    {
        foreach ($this->claimedInterfaces as $index => [$context, $interface]) {
            $this->libusb_release_interface($context, $interface);
            unset($this->claimedInterfaces[$index]);
        }

        $this->claimedInterfaces = array_values($this->claimedInterfaces);
    }

    public function __destruct()
    {
        $this->releaseInterfaces();
        $this->close();
        $this->libusb_exit(null);
    }

    public function close(): void
    {
        foreach ($this->opendLibusbContexts as $context) {
            $this->libusb_close($context);
        }

        $this->opendLibusbContexts = [];
    }

    public function __get($name)
    {
        return $this->ffi->{$name} ?? null;
    }

    public function __call($name, $arguments)
    {
        return $this->ffi->{$name}(...$arguments);
    }

    public static function __callStatic($name, $arguments)
    {
        return \FFI::{$name}($arguments);
    }

    public function __set($name, $value): void
    {
        $this->ffi->{$name} = $value;
    }

    #[\Override]
    public function getErrorByCode(int $errorCode): string
    {
        return (string) $this->libusb_error_name($errorCode);
    }

    #[\Override]
    public function devices(): array
    {
        $devicesData = $this->new('libusb_device **');

        $size = $this
            ->libusb_get_device_list(
                null,
                \FFI::addr($devicesData)
            );

        $devices = [];

        for ($i = 0; $i < $size; ++$i) {
            $device = $devicesData[$i] ?? null;
            if ($device === null) {
                continue;
            }

            $this->opendLibusbContexts[] = $context = $this->new('libusb_device_handle *');

            $lastError = $this
                ->libusb_open(
                    $device,
                    \FFI::addr($context)
                );

            $devices[] = new Device(
                $this,
                $device,
                new DeviceHandle($this, $context),
                $this->descriptor($device, $context),
            );
        }

        return $devices;
    }

    protected function descriptor(CData $context, CData $handleContext): DeviceDescriptorInterface
    {
        static $descriptorKeys = [
            'iManufacturer',
            'iProduct',
            'iSerialNumber',
        ];

        $descriptor = $this
            ->new('libusb_device_descriptor');

        $errorCode = $this
            ->libusb_get_device_descriptor(
                $context,
                \FFI::addr($descriptor)
            );

        if ($errorCode < 0) {
            throw new CannotRetrieveDeviceDescriptor(
                sprintf(
                    'An error occurred %s (%d)',
                    $this->getErrorByCode($errorCode),
                    $errorCode,
                ),
                $errorCode,
            );
        }

        $descriptorInfo = [];

        foreach ($descriptorKeys as $key) {
            $tmpUInt8Array = $this
                ->new('uint8_t[255]');

            $arraySize = \FFI::sizeof($tmpUInt8Array);

            $lastError = $this
                ->libusb_get_string_descriptor_ascii(
                    $handleContext,
                    $descriptor
                        ->{$key},
                    $tmpUInt8Array,
                    $arraySize
                );

            if ($lastError < 0) {
                $descriptorInfo[$key] = null;

                continue;
            }

            $descriptorInfo[$key] = $this->itoa($tmpUInt8Array);
        }

        return new DeviceDescriptor(
            $descriptor->idVendor,
            $descriptor->idProduct,
            $this->libusb_get_bus_number($context),
            $this->libusb_get_device_address($context),
            $descriptorInfo['iManufacturer'],
            $descriptorInfo['iProduct'],
            $descriptorInfo['iSerialNumber'],
        );
    }

    #[\Override]
    public function bulkTransportEndpoints(CData $context, CData $handleContext): array
    {
        $descriptors = $this
            ->new('libusb_config_descriptor *');

        $lastError = $this
            ->libusb_get_config_descriptor(
                $context,
                0,
                \FFI::addr($descriptors)
            );

        if ($lastError < 0) {
            throw new CannotRetrieveConfigDescriptor(
                sprintf('An error occurred %d', $lastError),
                $lastError,
            );
        }

        $bulkTransferEndpoints = [];

        $descriptor = $descriptors[0];

        for ($i = 0; $i < $descriptor->bNumInterfaces; ++$i) {
            $interface = $descriptor->interface[$i];
            for ($j = 0; $j < $interface->num_altsetting; ++$j) {
                $altSetting = $interface->altsetting[$j];
                for ($k = 0; $k < $altSetting->bNumEndpoints; ++$k) {
                    $endpoint = $altSetting->endpoint[$k];
                    $bulkTransferEndpoint = match ($endpoint->bmAttributes & 0x03) {
                        $this->LIBUSB_TRANSFER_TYPE_BULK => match ($endpoint->bEndpointAddress & 0x80) {
                            $this->LIBUSB_ENDPOINT_IN => new ReceiveBulkTransferEndpoint(
                                $this,
                                $handleContext,
                                $endpoint->bEndpointAddress,
                            ),
                            $this->LIBUSB_ENDPOINT_OUT => new SendBulkTransferEndpoint(
                                $this,
                                $handleContext,
                                $endpoint->bEndpointAddress,
                            ),
                            default => null,
                        },
                        default => null,
                    };

                    if ($bulkTransferEndpoint === null) {
                        continue;
                    }

                    $bulkTransferEndpoints[] = $bulkTransferEndpoint;
                }
            }
        }

        return $bulkTransferEndpoints;
    }

    #[\Override]
    public function setConfiguration(CData $context, int $configuration): void
    {
        $lastError = $this->libusb_set_configuration($context, $configuration);

        if ($lastError < 0) {
            throw new CannotSetConfiguration(
                sprintf(
                    'Cannot set configuration %s(%d)',
                    $this->getErrorByCode($lastError),
                    $lastError,
                ),
            );
        }
    }

    #[\Override]
    public function setClaimInterface(CData $context, int $interface): void
    {
        $lastError = $this->libusb_claim_interface($context, $interface);

        if ($lastError < 0) {
            throw new CannotClaimInterface(
                sprintf(
                    'Cannot claim an interface %s(%d)',
                    $this->getErrorByCode($lastError),
                    $lastError,
                ),
            );
        }

        $this->claimedInterfaces[] = [$context, $interface];
    }
}
