<?php

declare(strict_types=1);

namespace Device;

/**
 * The configuration provider for the Device module
 *
 * @see https://docs.zendframework.com/zend-component-installer/
 */
class ConfigProvider
{
    /**
     * Returns the configuration array
     *
     * To add a bit of a structure, each section is defined in a separate
     * method which returns an array with its configuration.
     */
    public function __invoke() : array
    {
        return [
            'dependencies' => $this->getDependencies(),
            'templates'    => $this->getTemplates(),
            'doctrine'     => $this->getEntities(),
        ];
    }

    /**
     * Returns the container dependencies
     */
    public function getDependencies() : array
    {
        return [
            'invokables' => [
            ],
            'factories'  => [
            ],
        ];
    }

    /**
     * Returns the templates configuration
     */
    public function getTemplates() : array
    {
        return [
            'paths' => [
                'device'    => [__DIR__ . '/../templates/'],
            ],
        ];
    }

    /**
     * Returns the doctrine entity configuration
     */
    public function getEntities() : array
    {
        return [
            'driver' => [
                'orm_default' => [
                    'drivers' => [
                        'Device\Entity' => 'device_entity'
                    ]
                ],
                'device_entity' => [
                    'class' => AnnotationDriver::class,
                    'cache' => 'array',
                    'paths' => __DIR__ . '/Entity'
                ]
            ]
        ];
    }
}
