<?php

namespace OpenTherm\Service;

use Device\Device\DeviceInterface;
use Device\Service\DeviceServiceInterface;
use Device\Service\GeneralDeviceService;
use OpenTherm\Processor\OpenThermUpdateProcessor;
use Queue\Message\Message;
use Queue\QueueManager;
use Zend\Expressive\Template\TemplateRendererInterface;

class OpenThermService implements DeviceServiceInterface
{

    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    /**
     * @var QueueManager
     */
    protected $queueManager;

    /**
     * OpenThermService constructor.
     */
    public function __construct(
        TemplateRendererInterface $templateRenderer,
        GeneralDeviceService $generalDeviceService,
        QueueManager $queueManager
    )
    {
        $this->templateRenderer = $templateRenderer;
        $this->generalDeviceService = $generalDeviceService;
        $this->queueManager = $queueManager;
    }

    /**
     * @param DeviceInterface $device
     * @return string
     */
    public function getSummary(DeviceInterface $device): string
    {
        $sensorData = $this->generalDeviceService->getLastSensorData($device);

        // map sensors to names
        $newSensorData = [];
        foreach ($sensorData as $sensor) {
            $newSensorData[$sensor->getSensor()] = $sensor;
        }
        $sensorData = $newSensorData;

        // get important sensors
        return $this->templateRenderer->render('open-therm::opentherm', [
            'device' => $device,
            'sensorData' => $sensorData
        ]);
    }

    /**
     * @param DeviceInterface $device
     */
    public function updateSensors(DeviceInterface $device)
    {
        $this->queueManager->push(new Message(
            OpenThermUpdateProcessor::class,
            [
                'device' => $device->getIdentifier()
            ]
        ));
    }
}
