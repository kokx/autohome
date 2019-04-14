<?php

namespace Device\Service\Factory;

use Device\Device\DeviceManager;
use Device\Service\DeviceService;
use Psr\Container\ContainerInterface;

class DeviceServiceFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        return new DeviceService($config['devices']);
    }
}
