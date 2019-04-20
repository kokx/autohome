<?php
declare(strict_types=1);

namespace Computer\Processor;

use Queue\Processor\ProcessorInterface;
use Device\Service\GeneralDeviceService;
use Queue\Message\Message;
use Symfony\Component\Process\Process;

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

        $hostline = $device->getUser() . '@' . $device->getHost();

        $process = new Process(['ssh', $hostline, 'sudo systemctl poweroff']);
        $process->run();

        // we don't check if the process ran correctly, we don't really need that
    }
}
