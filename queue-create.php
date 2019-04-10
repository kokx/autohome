<?php

use Queue\QueueManager;

chdir(__DIR__);
require 'vendor/autoload.php';

$container = require 'config/container.php';

/** @var QueueManager $manager */
$manager = $container->get(QueueManager::class);

$message = new \Queue\Message\Message('test', [
    'key' => 'value',
    'more' => 'data'
]);

$manager->push($message);

