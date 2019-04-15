<?php

namespace Device\Service;

use Psr\Container\ContainerInterface;

class DeviceServiceManager
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * DeviceManager constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get a device service.
     * @param string $name
     */
    public function getDeviceService(string $name) : DeviceServiceInterface
    {
        return $this->container->get($name);
    }
}
