<?php

declare(strict_types=1);

namespace Computer\Service;

use Device\Service\DeviceServiceInterface;
use Device\Device\DeviceInterface;
use Queue\QueueManager;
use Queue\Message\Message;
use Computer\Processor\UpdateStatusProcessor;
use Zend\Expressive\Template\TemplateRendererInterface;
use Device\Service\GeneralDeviceService;

/**
 * Service for the computer device.
 */
class ComputerService implements DeviceServiceInterface
{

    /**
     * @var QueueManager
     */
    protected $queueManager;

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    /**
     * @var TemplateRendererInterface
     */
    protected $templateRenderer;


    /**
     * ComputerService Constructor.
     */
    public function __construct(
        QueueManager $queueManager,
        GeneralDeviceService $generalDeviceService,
        TemplateRendererInterface $templateRenderer
    ) {
        $this->queueManager = $queueManager;
        $this->generalDeviceService = $generalDeviceService;
        $this->templateRenderer = $templateRenderer;
    }

    /**
     * {@inheritDoc}
     */
    public function getSummary(DeviceInterface $device) : string
    {
        $sensorData = $this->generalDeviceService->getLastSensorData($device);

        // map sensors to names
        $newSensorData = [];
        foreach ($sensorData as $sensor) {
            $newSensorData[$sensor->getSensor()] = $sensor;
        }
        $sensorData = $newSensorData;

        return $this->templateRenderer->render("computer::device", [
            'device' => $device,
            'sensorData' => $sensorData
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function showActuator(DeviceInterface $device, string $actuator) : string
    {
        switch ($actuator) {
            case 'online':
                $sensor = $this->generalDeviceService->getSensorState($device, 'online');
                return $this->templateRenderer->render('computer::online', [
                    'device' => $device,
                    'actuator' => $actuator,
                    'sensor' => $sensor
                ]);
            default:
                throw new \InvalidArgumentException("This device does not have the actuator '$actuator'.'");
        }
    }

    /**
     * {@inheritDoc}
     */
    public function setActuator(DeviceInterface $device, string $actuator, array $data) : void
    {
        // TODO: implement this
    }

    /**
     * {@inheritDoc}
     */
    public function updateSensors(DeviceInterface $device) : void
    {
        $this->queueManager->push(new Message(
            UpdateStatusProcessor::class,
            [
                'device' => $device->getIdentifier()
            ]
        ));
    }
}
