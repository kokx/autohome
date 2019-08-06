<?php

declare(strict_types=1);

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

    /**
     * Persist multiple statistics.
     * @param SensorStatistic[] $statistics
     */
    public function persistMultiple(array $statistics) : void
    {
        foreach ($statistics as $statistic) {
            $this->em->persist($statistic);
        }
        $this->em->flush();
    }
}
