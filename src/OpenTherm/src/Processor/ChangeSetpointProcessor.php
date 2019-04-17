<?php
declare(strict_types=1);

namespace OpenTherm\Processor;

use Queue\Message\Message;
use Queue\Processor\ProcessorInterface;

class ChangeSetpointProcessor implements ProcessorInterface
{

    public function process(Message $message): void
    {
        var_dump($message);
    }
}
