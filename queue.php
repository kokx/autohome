<?php

use Queue\QueueManager;

chdir(__DIR__);
require 'vendor/autoload.php';

$container = require 'config/container.php';

/** @var QueueManager $manager */
$manager = $container->get(QueueManager::class);

$manager->processAll();