<?php

declare(strict_types=1);

namespace Computer\Device;

use Device\Device\DeviceInterface;
use Computer\Service\ComputerService;

/**
 * Device which is a computer. Intended so we can turn it on and off easily.
 *
 * Uses wake-on-lan to turn it on. And SSH to send `sudo systemctl poweroff`.
 */
class Computer implements DeviceInterface
{

    /**
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port = 22;


    /**
     * Computer constructor.
     */
    public function __construct(string $identifier, array $options)
    {
        if (!isset($options['host'])) {
            throw new \InvalidArgumentException("No host given.");
        }

        $this->identifier = $identifier;
        $this->host = $options['host'];

        if (isset($options['port'])) {
            $this->port = (int) $options['port'];
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier() : string
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function getName() : string
    {
        return 'Computer ' . $this->getIdentifier();
    }

    /**
     * {@inheritDoc}
     */
    public function getDeviceServiceName() : string
    {
        return ComputerService::class;
    }

    public function getHost() : string
    {
        return $this->host;
    }

    public function getPort() : int
    {
        return $this->port;
    }
}
