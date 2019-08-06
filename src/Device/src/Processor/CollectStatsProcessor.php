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

        // TODO: determine day
        $date = new \DateTime('2019-07-02 00:00:00');

        foreach ($devices as $device) {
            $this->generalDeviceService->combineStats($device, $date);
        }
    }
}
