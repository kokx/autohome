<?php

use Queue\QueueManager;
use Queue\Processor\ProcessorInterface;
use Queue\Processor\ProcessorManager;
use Queue\Message\Message;

chdir(__DIR__);
require 'vendor/autoload.php';

$container = require 'config/container.php';

/** @var QueueManager $manager */
$manager = $container->get(QueueManager::class);
/** @var ProcessorManager $processorManager */
$processorManager = $container->get(ProcessorManager::class);

class TestProcessor implements ProcessorInterface {
    public function process(Message $message): void
    {
        var_dump('Test!!!!!!!');
        var_dump($message);
    }
}

$processorManager->setAlias('test', TestProcessor::class);

$message = new Message('test', [
    'key' => 'value',
    'more' => 'data'
]);

$manager->push($message);

$manager->processAll();