<?php

namespace Device\Device;

interface DeviceInterface
{

    /**
     * Create a new device
     * @param string $identifier
     * @param array $options
     */
    public function __construct(string $identifier, array $options);

    /**
     * Get the device identifier.
     * @return string
     */
    public function getIdentifier() : string;

    /**
     * Get the name of the device type.
     * @return string
     */
    public function getName() : string;

    /**
     * Get device service name.
     * @return string
     */
    public function getDeviceServiceName() : string;
}
