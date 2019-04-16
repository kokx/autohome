<?php

namespace OpenTherm\Service;

use Device\Device\DeviceInterface;
use Device\Service\DeviceServiceInterface;
use Device\Service\GeneralDeviceService;
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
     * OpenThermService constructor.
     */
    public function __construct(TemplateRendererInterface $templateRenderer, GeneralDeviceService $generalDeviceService)
    {
        $this->templateRenderer = $templateRenderer;
        $this->generalDeviceService = $generalDeviceService;
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
}
