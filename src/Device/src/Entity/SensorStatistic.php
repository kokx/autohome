<?php

declare(strict_types=1);

namespace Device\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Represents the statistics of a single day for a single sensor.
 * @ORM\Entity
 */
class SensorStatistic
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
     * @ORM\Column(type="string")
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
     * Day of this log entry.
     * @ORM\Column(type="date")
     * @var \DateTime
     */
    protected $day;

    /**
     * Maximum of the day.
     * @ORM\Column(type="string")
     * @var string
     */
    protected $maximum;

    /**
     * Minimum of the day.
     * @ORM\Column(type="string")
     * @var string
     */
    protected $minimum;

    /**
     * Average of the day.
     * @ORM\Column(type="string")
     * @var string
     */
    protected $average;

    /**
     * Median of the day.
     * @ORM\Column(type="string")
     * @var string
     */
    protected $median;


    /**
     * Get the ID.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get the device
     * @return string
     */
    public function getDevice() : string
    {
        return $this->device;
    }

    /**
     * Set the device
     * @param string $device
     */
    public function setDevice(string $device)
    {
        $this->device = $device;
    }

    /**
     * Get the sensor
     * @return string
     */
    public function getSensor() : string
    {
        return $this->sensor;
    }

    /**
     * Set the sensor
     * @param string $sensor
     */
    public function setSensor(string $sensor)
    {
        $this->sensor = $sensor;
    }

    /**
     * Get the day.
     * @return \DateTime
     */
    public function getDay() : \DateTime
    {
        return $this->day;
    }

    /**
     * Set the day.
     * @param \DateTime $day
     */
    public function setDay(\DateTime $day)
    {
        $this->day = $day;
    }

    /**
     * Get the maximum
     * @return string
     */
    public function getMaximum() : string
    {
        return $this->max;
    }

    /**
     * Set the maximum
     * @param string $maximum
     */
    public function setMaximum(string $maximum)
    {
        $this->maximum = $maximum;
    }

    /**
     * Get the minimum
     * @return string
     */
    public function getMinimum() : string
    {
        return $this->minimum;
    }

    /**
     * Set the minimum
     * @param string $minimum
     */
    public function setMinimum(string $minimum)
    {
        $this->minimum = $minimum;
    }

    /**
     * Get the average
     * @return string
     */
    public function getAverage() : string
    {
        return $this->average;
    }

    /**
     * Set the average
     * @param string $average
     */
    public function setAverage(string $average)
    {
        $this->average = $average;
    }

    /**
     * Get the median
     * @return string
     */
    public function getMedian() : string
    {
        return $this->median;
    }

    /**
     * Set the median
     * @param string $median
     */
    public function setMedian(string $median)
    {
        $this->median = $median;
    }
}
