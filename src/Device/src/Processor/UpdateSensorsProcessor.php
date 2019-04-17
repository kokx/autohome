<?php

namespace Device\Processor;

use Device\Service\GeneralDeviceService;
use Queue\Message\Message;
use Queue\Processor\ProcessorInterface;

class UpdateSensorsProcessor implements ProcessorInterface
{

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    /**
     * UpdateSensorsProcessor constructor.
     */
    public function __construct(GeneralDeviceService $generalDeviceService)
    {
        $this->generalDeviceService = $generalDeviceService;
    }

    public function process(Message $message): void
    {
        $devices = $this->generalDeviceService->getAllDevices();

        foreach ($devices as $device) {
            $service = $this->generalDeviceService->getDeviceService($device);

            $service->updateSensors($device);
        }
    }
}
