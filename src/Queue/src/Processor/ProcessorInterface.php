<?php

namespace Queue\Processor;

use Queue\Message\Message;

/**
 * Processors must implement this interface.
 */
interface ProcessorInterface
{

    /**
     * Process a message.
     * @param Message $message
     */
    public function process(Message $message) : void;
}
