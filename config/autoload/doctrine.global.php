<?php

declare(strict_types=1);

return [
    'doctrine' => [
        'driver' => [
            'orm_default' => [
                'class' => \Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain::class,
                // specified in modules
                'drivers' => [],
            ],
        ],
    ]
];
