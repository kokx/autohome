<?php

namespace Device\Service;

use Device\Device\DeviceInterface;
use Device\Entity\SensorLog;
use Device\Mapper\SensorLogMapper;

/**
 * Provides several services for devices. Mainly centered around data access.
 */
class GeneralDeviceService
{

    /**
     * @var SensorLogMapper
     */
    protected $sensorLogMapper;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var DeviceServiceManager
     */
    protected $deviceManager;

    /**
     * DeviceService constructor.
     * @param array $config
     * @param SensorLogMapper $sensorLogMapper
     * @param DeviceServiceManager $deviceManager
     */
    public function __construct(array $config, SensorLogMapper $sensorLogMapper, DeviceServiceManager $deviceManager)
    {
        $this->config = $config;
        $this->sensorLogMapper = $sensorLogMapper;
        $this->deviceManager = $deviceManager;
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

        return new $class($name, $config['options']);
    }

    /**
     * Get the service for a device.
     * @param DeviceInterface $device
     */
    public function getDeviceService(DeviceInterface $device) : DeviceServiceInterface
    {
        return $this->deviceManager->getDeviceService($device->getDeviceServiceName());
    }

    /**
     * Log data from several sensors
     *
     * @param string $deviceName
     * @param array $data
     */
    public function logSensorData(string $deviceName, array $data) : void
    {
        if (!isset($this->config[$deviceName])) {
            throw new \InvalidArgumentException("Cannot log data for non-existing device '$deviceName''.");
        }

        $log = [];

        foreach ($data as $sensorName => $state) {
            $logItem = new SensorLog();

            $logItem->setDevice($deviceName);
            $logItem->setSensor($sensorName);
            $logItem->setState($state);

            $log[] = $logItem;
        }

        $this->sensorLogMapper->persistMultiple($log);
    }

    /**
     * Get the last sensor data
     *
     * @return SensorLog[]
     */
    public function getLastSensorData(DeviceInterface $device) : iterable
    {
        return $this->sensorLogMapper->findLastSensorData($device->getName());
    }
}
