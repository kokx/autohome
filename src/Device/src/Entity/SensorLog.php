<?php

namespace Device\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents the state of a sensor at a certain moment
 * @ORM\Entity
 */
class SensorLog
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * Name of the device for this log entry.
     * ORM\Column(type="string")
     */
    protected $device;

    /**
     * Name of the sensor for this log entry.
     * @ORM\Column(type="string")
     */
    protected $sensor;

    /**
     * Sensor state for this entry.
     *
     * This could basically be any value. This should be interpreted by the
     * device/sensor.
     *
     * @ORM\Column(type="string")
     */
    protected $state;

    /**
     * Moment this log entry was created.
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
}
