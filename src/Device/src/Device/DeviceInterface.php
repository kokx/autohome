<?php

namespace Device\Device;

interface DeviceInterface
{

    /**
     * Create a new device
     * @param array $options
     */
    public function __construct(array $options);

    /**
     * Get device service name.
     * @return string
     */
    public function getDeviceServiceName() : string;
}
