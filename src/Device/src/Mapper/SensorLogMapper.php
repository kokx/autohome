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
}