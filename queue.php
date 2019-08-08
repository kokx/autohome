<?php

use Queue\QueueManager;

chdir(__DIR__);
require 'vendor/autoload.php';

$container = require 'config/container.php';

/** @var QueueManager $manager */
$manager = $container->get(QueueManager::class);

$manager->addRepeater(new \Queue\MessageRepeater(
    new \Queue\Message\Message(
        \Device\Processor\UpdateSensorsProcessor::class,
        []
    ),
    new \DateInterval('PT5M')
));

// collect statistics every 4 hours
$manager->addRepeater(new \Queue\MessageRepeater(
    new \Queue\Message\Message(
        \Device\Processor\CollectStatsProcessor::class,
        []
    ),
    new \DateInterval('PT4H')
));

// vacuum the database every day
$manager->addRepeater(new \Queue\MessageRepeater(
    new \Queue\Message\Message(
        \App\Processor\VacuumProcessor::class,
        []
    ),
    new \DateInterval('PT24H')
));

$manager->processAll();
