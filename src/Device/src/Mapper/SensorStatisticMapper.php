<?php

namespace Device\Mapper;

use Device\Entity\SensorStatistic;
use Doctrine\ORM\EntityManager;

class SensorStatisticMapper
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * SensorStatisticMapper constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Persist.
     * @param SensorStatistic $statistic
     */
    public function persist(SensorStatistic $statistic) : void
    {
        $this->em->persist($statistic);
        $this->em->flush();
    }
}
