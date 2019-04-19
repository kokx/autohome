<?php

declare(strict_types=1);

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
    protected $identifier;

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
     */
    public function __construct(string $identifier, array $options)
    {
        if (!isset($options['host'])) {
            throw new \InvalidArgumentException("No host given.");
        }
        if (!isset($options['port'])) {
            throw new \InvalidArgumentException("No port given.");
        }

        $this->identifier = $identifier;
        $this->host = $options['host'];
        $this->port = $options['port'];
    }

    /**
     * {@inheritDoc}
     */
    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    /**
     * {@inheritDoc}
     */
    public function getName(): string
    {
        return 'OpenTherm Gateway ' . $this->getIdentifier();
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
