<?php

namespace Device\Service\Factory;

use Device\Mapper\SensorLogMapper;
use Device\Service\DeviceServiceManager;
use Device\Service\GeneralDeviceService;
use Psr\Container\ContainerInterface;
use Device\Mapper\SensorStatisticMapper;

class GeneralDeviceServiceFactory
{

    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');
        $mapper = $container->get(SensorLogMapper::class);
        $statisticMapper = $container->get(SensorStatisticMapper::class);
        $serviceManager = $container->get(DeviceServiceManager::class);

        return new GeneralDeviceService($config['devices'], $mapper, $statisticMapper, $serviceManager);
    }
}
