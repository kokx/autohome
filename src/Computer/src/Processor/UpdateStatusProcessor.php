<?php

declare(strict_types=1);

namespace Computer\Processsor;

use Queue\Processor\ProcessorInterface;
use Queue\Message\Message;
use Device\Service\GeneralDeviceService;
use Computer\Device\Computer;

/**
 * Check the online status of a computer.
 */
class UpdateStatusProcessor implements ProcessorInterface
{

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    /**
     * Create a computer status processor.
     */
    public function __construct(GeneralDeviceService $generalDeviceService)
    {
        $this->generalDeviceService = $generalDeviceService;
    }

    /**
     * {@inheritDoc}
     */
    public function process(Message $message): void
    {
        $payload = $message->getPayload();

        if (!isset($payload['device'])) {
            throw new \RuntimeException("No device given.");
        }

        /** @var Computer $device */
        $device = $this->generalDeviceService->getDevice($payload['device']);

        // we suppress errors here, only because if there is an error, we simply want to
        // report that the host is down, we don't care about the actual error
        $sock = @fsockopen($device->getHost(), $device->getPort());
        $online = false;

        if ($sock) {
            fclose($sock);
            $online = true;
        }

        $this->generalDeviceService->logSensorData($device, ['online' => $online]);
    }
}
