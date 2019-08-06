<?php

declare(strict_types=1);

namespace Device\Processor;

use Device\Service\GeneralDeviceService;
use Queue\Message\Message;
use Queue\Processor\ProcessorInterface;

class CollectStatsProcessor implements ProcessorInterface
{

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    public function __construct(GeneralDeviceService $generalDeviceService)
    {
        $this->generalDeviceService = $generalDeviceService;
    }

    /**
     * Combine statistics for every device, for a single day.
     */
    public function process(Message $message): void
    {
        $devices = $this->generalDeviceService->getAllDevices();

        /**
         * To determine the day:
         *
         * Find the first ('lowest') day such that:
         * - It is at least 3 days ago (not today, not yesterday, not the day before yesterday)
         * - The day has SensorLog entries
         *
         * This day may not exist
         */

        // TODO: determine day
        $date = new \DateTime('2019-07-02 00:00:00');

        foreach ($devices as $device) {
            $this->generalDeviceService->combineStats($device, $date);
        }

        // TODO: remove all sensor logs on $date
    }
}
