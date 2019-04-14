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
     * @var int
     */
    protected $id;

    /**
     * Name of the device for this log entry.
     * ORM\Column(type="string")
     * @var string
     */
    protected $device;

    /**
     * Name of the sensor for this log entry.
     * @ORM\Column(type="string")
     * @var string
     */
    protected $sensor;

    /**
     * Sensor state for this entry.
     *
     * This could basically be any value. This should be interpreted by the
     * device/sensor.
     *
     * @ORM\Column(type="string")
     * @var string
     */
    protected $state;

    /**
     * Moment this log entry was created.
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @var \DateTime
     */
    protected $createdAt;


    /**
     * SensorLog constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getDevice(): string
    {
        return $this->device;
    }

    /**
     * @param string $device
     */
    public function setDevice(string $device): void
    {
        $this->device = $device;
    }

    /**
     * @return string
     */
    public function getSensor(): string
    {
        return $this->sensor;
    }

    /**
     * @param string $sensor
     */
    public function setSensor(string $sensor): void
    {
        $this->sensor = $sensor;
    }

    /**
     * @return string
     */
    public function getState(): string
    {
        return $this->state;
    }

    /**
     * @param string $state
     */
    public function setState(string $state): void
    {
        $this->state = $state;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }
}
