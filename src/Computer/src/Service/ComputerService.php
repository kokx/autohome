<?php

declare(strict_types=1);

namespace Computer\Service;

use Device\Service\DeviceServiceInterface;
use Device\Device\DeviceInterface;

/**
 * Service for the computer device.
 */
class ComputerService implements DeviceServiceInterface
{

    /**
     * {@inheritDoc}
     */
    public function getSummary(DeviceInterface $device) : string
    {
        return "TODO";
    }

    /**
     * {@inheritDoc}
     */
    public function showActuator(DeviceInterface $device, string $actuator) : string
    {
        return "TODO";
    }

    /**
     * {@inheritDoc}
     */
    public function setActuator(DeviceInterface $device, string $actuator, array $data) : void
    {
        // TODO: implement this
    }

    /**
     * {@inheritDoc}
     */
    public function updateSensors(DeviceInterface $device) : void
    {
        // TODO: implement this
    }
}
