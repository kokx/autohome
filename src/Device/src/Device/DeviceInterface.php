<?php

namespace Device\Device;

interface DeviceInterface
{

    /**
     * Create a new device
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options);

    /**
     * Get the device name.
     * @return string
     */
    public function getName() : string;

    /**
     * Get device service name.
     * @return string
     */
    public function getDeviceServiceName() : string;
}
