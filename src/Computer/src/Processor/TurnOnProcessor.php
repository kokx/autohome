<?php
declare(strict_types=1);

namespace Computer\Processor;

use Queue\Processor\ProcessorInterface;
use Queue\Message\Message;
use Device\Service\GeneralDeviceService;

/**
 * Turn the computer on.
 */
class TurnOnProcessor implements ProcessorInterface
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
     * Turn on the computer.
     */
    public function process(Message $message): void
    {
        $payload = $message->getPayload();

        if (!isset($payload['device'])) {
            throw new \RuntimeException("No device given.");
        }

        /** @var \Computer\Device\Computer $device */
        $device = $this->generalDeviceService->getDevice($payload['device']);

        // TODO: send wake-on-lan packet
        echo 'TODO: send wake-on-lan packet to ' . $device->getMac() . "\n";
    }
}
