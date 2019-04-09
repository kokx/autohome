<?php

use Queue\QueueManager;
use Queue\Message\PlainMessage;

chdir(__DIR__);
require 'vendor/autoload.php';

$container = require 'config/container.php';

/** @var QueueManager $manager */
$manager = $container->get(QueueManager::class);

$message = new PlainMessage('test', [
    'key' => 'value',
    'more' => 'data'
]);

$manager->push($message);

$manager->processAll();