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
}
