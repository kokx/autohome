<?php

namespace Device\Mapper;

use Device\Entity\SensorLog;
use Doctrine\ORM\EntityManager;

class SensorLogMapper
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * SensorLogMapper constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Persist multiple sensors.
     * @param SensorLog[]
     */
    public function persistMultiple(array $sensors) : void
    {
        foreach ($sensors as $sensor) {
            $this->em->persist($sensor);
        }

        $this->em->flush();
    }

    /**
     * Find the last sensor data for a device.
     * @param string $deviceName
     */
    public function findLastSensorData(string $deviceName) : iterable
    {
        $dql = "SELECT s FROM Device\Entity\SensorLog s
            WHERE s.device = :device
            GROUP BY s.device, s.sensor
            HAVING s.createdAt = MAX(s.createdAt)
            ORDER BY s.id ASC";

        $query = $this->em->createQuery($dql);

        $query->execute([
            'device' => $deviceName
        ]);

        return $query->getResult();
    }

    /**
     * Find the last sensor state.
     * @param string $device
     * @param string $sensor
     */
    public function findSensorState(string $device, string $sensor) : SensorLog
    {
        $dql = "SELECT s FROM Device\Entity\SensorLog s
            WHERE s.device = :device
                AND s.sensor = :sensor
            GROUP BY s.device, s.sensor
            HAVING s.createdAt = MAX(s.createdAt)";

        $query = $this->em->createQuery($dql);

        $query->execute([
            'device' => $device,
            'sensor' => $sensor
        ]);

        return $query->getSingleResult();
    }

    /**
     * Find all data for a sensor.
     * @param string $device
     * @param string $sensor
     * @return SensorLog[]
     */
    public function findSensorLog(string $device, string $sensor) : array
    {
        $dql = "SELECT s FROM Device\Entity\SensorLog s
            WHERE s.device = :device
                AND s.sensor = :sensor
                AND s.createdAt > :date
            ORDER BY s.createdAt ASC";

        $query = $this->em->createQuery($dql);

        $query->execute([
            'device' => $device,
            'sensor' => $sensor,
            'date' => new \DateTime('-24 hour')
        ]);

        return $query->getResult();
    }
}
