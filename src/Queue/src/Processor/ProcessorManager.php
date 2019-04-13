<?php

namespace Queue\Processor;

use Psr\Container\ContainerInterface;

/**
 * Manages processors, and instantiates them when needed.
 *
 * This uses a DI container to instantiate processors.
 */
class ProcessorManager
{

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Map of message names to processors.
     * @var array
     */
    protected $aliases;

    /**
     * ProcessorManager constructor.
     * @param ContainerInterface $container
     * @param array $aliases Map from message names to processors.
     */
    public function __construct(ContainerInterface $container, array $aliases = [])
    {
        $this->container = $container;
    }

    /**
     * Set a processor alias.
     * @param string $name
     * @param string $processor
     */
    public function setAlias(string $name, string $processor) : void
    {
        $this->aliases[$name] = $processor;
    }

    /**
     * Set multiple processor aliases.
     * @param array $aliases
     */
    public function setAliases(array $aliases) : void
    {
        foreach ($aliases as $name => $processor) {
            $this->setAlias($name, $processor);
        }
    }

    /**
     * Get a processor by message name.
     * @param string $name
     * @return ProcessorInterface
     */
    public function get(string $name) : ProcessorInterface
    {
        // first resolve any aliases for this name
        while (isset($this->aliases[$name])) {
            $name = $this->aliases[$name];
        }

        return $this->container->get($name);
    }
}
