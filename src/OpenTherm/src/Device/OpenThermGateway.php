<?php

namespace OpenTherm\Device;

use Device\Device\DeviceInterface;

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
    protected $host;

    /**
     * @var int
     */
    protected $port;

    /**
     * OpenThermGateway constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (!isset($options['host'])) {
            throw new \InvalidArgumentException("No host given.");
        }
        if (!isset($options['port'])) {
            throw new \InvalidArgumentException("No port given.");
        }

        $this->host = $options['host'];
        $this->port = $options['port'];
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
