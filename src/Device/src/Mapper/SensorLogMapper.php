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
     * Find the first day with sensor data.
     */
    public function findFirstDayWithData(string $device) : string
    {
        $sql = "SELECT date(s.created_at) as created_date
            FROM SensorLog as s
            WHERE s.device = :device
            ORDER BY s.created_at ASC
            LIMIT 1";

        $stmt = $this->em->getConnection()->prepare($sql);

        $stmt->execute([
            'device' => $device
        ]);

        $row = $stmt->fetch();

        if ($row === null || empty($row)) {
            return null;
        }

        return $row['created_date'];
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
     * Find todays data for a sensor.
     * @param string $device
     * @param string $sensor
     * @return SensorLog[]
     */
    public function findDaySensorLog(string $device, string $sensor) : array
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

    /**
     * Find todays data for a sensor.
     * @param string $device
     * @param \DateTime $day
     * @return array
     */
    public function findStatsForDay(string $device, \DateTime $day) : array
    {
        $sql = "SELECT sensor,
                       date(created_at) as created_date,
                       min(state) as minimum,
                       max(state) as maximum,
                       avg(state) as average
                    FROM SensorLog
                    WHERE device = :device
                      AND created_at >= datetime(:startdate)
                      AND created_at < datetime(:enddate)
                    GROUP BY sensor";

        $stmt = $this->em->getConnection()->prepare($sql);

        $end = (clone $day)->add(new \DateInterval('PT24H'));

        $stmt->execute([
            'device' => $device,
            'startdate' => $day->format('Y-m-d H:i:s'),
            'enddate' => $end->format('Y-m-d H:i:s'),
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Find monthly stats for a sensor.
     *
     * @param string $device
     * @param string $sensor
     * @param string $period
     * @return array
     */
    public function findPeriodSensorStats(string $device, string $sensor, string $period = 'month') : array
    {
        if (!in_array($period, ['month', 'year'])) {
            throw new \InvalidArgumentException("Period '$period' is not 'month' or 'year'.");
        }
        $sql = "SELECT date(created_at) as created_date,
                       min(state) as minimum,
                       max(state) as maximum,
                       avg(state) as average
                    FROM SensorLog
                    WHERE device = :device
                      AND sensor = :sensor
                      AND date(created_at) >= date('now', '-1 $period')
                    GROUP BY date(created_at)";

        $stmt = $this->em->getConnection()->prepare($sql);

        $stmt->execute([
            'device' => $device,
            'sensor' => $sensor
        ]);

        return $stmt->fetchAll();
    }

    /**
     * Remove data for a single day.
     *
     * @param string $device
     * @param \DateTime $day
     */
    public function removeForDay(string $device, \DateTime $day) : void
    {
        $sql = "DELETE FROM SensorLog as s
                    WHERE s.device = :device
                      AND date(s.created_at) = :day";

        $stmt = $this->em->getConnection()->prepare($sql);

        $stmt->execute([
            'device' => $device,
            'day' => $day->format('Y-m-d')
        ]);
    }
}
