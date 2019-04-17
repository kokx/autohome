<?php

namespace Device\Service;

use Device\Device\DeviceInterface;

interface DeviceServiceInterface
{

    /**
     * Get a summary for a device (as HTML).
     */
    public function getSummary(DeviceInterface $device) : string;

    /**
     * Show an actuator.
     */
    public function showActuator(DeviceInterface $device, string $actuator) : string;

    /**
     * Set an actuator.
     */
    public function setActuator(DeviceInterface $device, string $actuator, array $data) : void;

    /**
     * Update the sensors of a device.
     */
    public function updateSensors(DeviceInterface $device) : void;
}
