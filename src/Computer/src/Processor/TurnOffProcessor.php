<?php
declare(strict_types=1);

namespace Computer\Processor;

use Queue\Processor\ProcessorInterface;
use Device\Service\GeneralDeviceService;
use Queue\Message\Message;

/**
 * Turn the computer off.
 */
class TurnOffProcessor implements ProcessorInterface
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
     * Turn off the computer.
     */
    public function process(Message $message): void
    {
        $payload = $message->getPayload();

        if (!isset($payload['device'])) {
            throw new \RuntimeException("No device given.");
        }

        /** @var \Computer\Device\Computer $device */
        $device = $this->generalDeviceService->getDevice($payload['device']);

        // TODO: ssh `sudo systemctl poweroff` to device
        echo "TODO: ssh 'sudo systemctl poweroff' to "  . $device->getMac() . "\n";
    }
}
