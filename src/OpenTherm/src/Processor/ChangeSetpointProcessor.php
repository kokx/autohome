<?php
declare(strict_types=1);

namespace OpenTherm\Processor;

use Device\Service\GeneralDeviceService;
use OpenTherm\Device\OpenThermGateway;
use Queue\Message\Message;
use Queue\Processor\ProcessorInterface;

class ChangeSetpointProcessor implements ProcessorInterface
{

    /**
     * @var GeneralDeviceService
     */
    protected $generalDeviceService;

    /**
     * {@inheritDoc}
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
        if (!isset($payload['actuator'])) {
            throw new \RuntimeException("No actuator given.");
        }
        if (!isset($payload['setpoint'])) {
            throw new \RuntimeException("No setpoint given.");
        }

        /** @var OpenThermGateway $device */
        $device = $this->generalDeviceService->getDevice($payload['device']);

        $socket = fsockopen($device->getHost(), $device->getPort());

        // CR-LF is required. The gateway won't respond otherwise
        fwrite($socket, "TT={$payload['setpoint']}\r\n");

        // we don't care about what we get back, but we read it
        // to prevent closing the connection too soon

        $data = "";

        $newlines = 0;
        while (!feof($socket) && $newlines < 1) {
            $out = fread($socket, 4096);
            $newlines += substr_count($out, "\n");
            $data .= $out;
        }

        fclose($socket);
    }
}
