<?php

namespace OpenTherm\Device;

use Device\Device\DeviceInterface;
use OpenTherm\Service\OpenThermService;

/**
 * Device based on the OpenTherm Gateway, by Schelte Bron.
 *
 * @link http://otgw.tclcode.com/ The OpenTherm Gateway
 */
class OpenThermGateway implements DeviceInterface
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * OpenThermGateway constructor.
     * @param string $name
     * @param array $options
     */
    public function __construct(string $name, array $options)
    {
        if (!isset($options['host'])) {
            throw new \InvalidArgumentException("No host given.");
        }
        if (!isset($options['port'])) {
            throw new \InvalidArgumentException("No port given.");
        }

        $this->name = $name;
        $this->host = $options['host'];
        $this->port = $options['port'];
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * {@inheritDoc}
     */
    public function getDeviceServiceName(): string
    {
        return OpenThermService::class;
    }

    /**
     * @return string
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }
}
