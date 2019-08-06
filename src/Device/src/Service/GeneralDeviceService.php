<?php

namespace Device\Service;

use Device\Device\DeviceInterface;
use Device\Entity\SensorLog;
use Device\Mapper\SensorLogMapper;
use Device\Entity\SensorStatistic;

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
     * Get all devices.
     * @return DeviceInterface[]
     */
    public function getAllDevices()
    {
        $devices = [];
        foreach ($this->config as $name => $config) {
            $devices[$name] = $this->getDevice($name);
        }
        return $devices;
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
     * @param DeviceInterface $device
     * @param array $data
     */
    public function logSensorData(DeviceInterface $device, array $data) : void
    {
        if (!isset($this->config[$device->getIdentifier()])) {
            throw new \InvalidArgumentException(
                "Cannot log data for non-existing device '{$device->getIdentifier()}''."
            );
        }

        $log = [];

        foreach ($data as $sensorName => $state) {
            $logItem = new SensorLog();

            $logItem->setDevice($device->getIdentifier());
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
        return $this->sensorLogMapper->findLastSensorData($device->getIdentifier());
    }

    /**
     * Get the last state of a sensor.
     *
     * @return SensorLog
     */
    public function getSensorState(DeviceInterface $device, string $sensor) : SensorLog
    {
        return $this->sensorLogMapper->findSensorState($device->getIdentifier(), $sensor);
    }

    /**
     * Combine statistics of a sensor for one day.
     */
    public function combineStats(DeviceInterface $device, string $sensor, \DateTime $day)
    {
        $stats = $this->sensorLogMapper->findStatsForDay($device->getIdentifier(), $sensor, $day);

        $entity = new SensorStatistic();

        $entity->setDevice($device->getIdentifier());
        $entity->setSensor($sensor);
        $entity->setDay($day);
        $entity->setMaximum($stats['maximum']);
        $entity->setMinimum($stats['minimum']);
        $entity->setAverage($stats['average']);

        // TODO: persist statistics
        return $entity;
    }

    /**
     * Get the day log of a sensor.
     *
     * @return SensorLog[]
     */
    public function getDaySensorLog(DeviceInterface $device, string $sensor) : array
    {
        return $this->sensorLogMapper->findDaySensorLog($device->getIdentifier(), $sensor);
    }

    /**
     * Get the log of a sensor.
     *
     * @return array
     */
    public function getMonthSensorStats(DeviceInterface $device, string $sensor) : array
    {
        return $this->sensorLogMapper->findPeriodSensorStats($device->getIdentifier(), $sensor);
    }

    /**
     * Get the year log of a sensor.
     *
     * @return array
     */
    public function getYearSensorStats(DeviceInterface $device, string $sensor) : array
    {
        return $this->sensorLogMapper->findPeriodSensorStats($device->getIdentifier(), $sensor, 'year');
    }
}
