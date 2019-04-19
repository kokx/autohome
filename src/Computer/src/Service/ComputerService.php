<?php

declare(strict_types=1);

namespace Computer\Service;

use Device\Service\DeviceServiceInterface;
use Device\Device\DeviceInterface;
use Queue\QueueManager;
use Queue\Message\Message;
use Computer\Processor\UpdateStatusProcessor;

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
     * ComputerService Constructor.
     */
    public function __construct(QueueManager $queueManager)
    {
        $this->queueManager = $queueManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getSummary(DeviceInterface $device) : string
    {
        return "TODO";
    }

    /**
     * {@inheritDoc}
     */
    public function showActuator(DeviceInterface $device, string $actuator) : string
    {
        return "TODO";
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
