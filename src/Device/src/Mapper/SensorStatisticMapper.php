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
    }

    /**
     * Execute a statistic transaction.
     *
     * @param callable $transaction
     */
    public function transactional(callable $transaction) : void
    {
        $this->em->transactional($transaction);
    }

    /**
     * Find the sensor stats for a period.
     *
     * @param string $device
     * @param string $sensor
     * @param string $period
     */
    public function findPeriodSensorStats(string $device, string $sensor, string $period = 'month') : array
    {
        if (!in_array($period, ['month', 'year'])) {
            throw new \InvalidArgumentException("Period '$period' is not 'month' or 'year'.");
        }

        $sql = "SELECT date(day) as created_date, minimum, maximum, average
                    FROM SensorStatistic
                    WHERE device = :device
                      AND sensor = :sensor
                      AND date(day) >= date('now', '-1 $period')";

        $stmt = $this->em->getConnection()->prepare($sql);

        $stmt->execute([
            'device' => $device,
            'sensor' => $sensor
        ]);

        return $stmt->fetchAll();
    }
}
