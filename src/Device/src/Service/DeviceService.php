<?php

namespace Device\Service;

use Device\Device\DeviceInterface;
use Device\Device\DeviceManager;

/**
 * Provides several services for devices. Mainly centered around data access.
 */
class DeviceService
{

    /**
     * @var array
     */
    protected $config;

    /**
     * DeviceService constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Get a device.
     * @param string $name
     * @return DeviceInterface
     */
    public function getDevice(string $name) : DeviceInterface
    {
        if (!isset($this->config[$name])) {
            throw new \InvalidArgumentException("Device with name '$name' not found.");
        }

        $config = $this->config[$name];

        $class = $config['type'];

        return new $class($config['options']);
    }
}
