<?php

declare(strict_types=1);

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'params' => [
                    'url' => 'sqlite:///data/database.sqlite'
                ]
            ]
        ]
    ]
];
