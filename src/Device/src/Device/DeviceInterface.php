<?php

namespace Device\Device;

interface DeviceInterface
{

    /**
     * Create a new device
     * @param array $options
     */
    public function __construct(array $options);
}
