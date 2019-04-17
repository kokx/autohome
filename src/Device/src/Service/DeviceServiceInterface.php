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
     * Update the sensors of a device.
     */
    public function updateSensors(DeviceInterface $device);
}
