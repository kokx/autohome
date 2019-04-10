<?php

namespace Queue;

use Queue\Entity\QueueMessage;
use Queue\Mapper\QueueMapper;
use Queue\Message\Message;
use Queue\Processor\ProcessorManager;

class QueueManager
{

    /**
     * @var QueueMapper
     */
    protected $queueMapper;

    /**
     * @var ProcessorManager
     */
    protected $processorManager;

    /**
     * @param QueueMapper $mapper
     * @param ProcessorManager $processorManager
     */
    public function __construct(QueueMapper $mapper, ProcessorManager $processorManager)
    {
        $this->queueMapper = $mapper;
        $this->processorManager = $processorManager;
    }

    /**
     * Process a single message.
     */
    public function process(Message $message) : void
    {
        $processor = $this->processorManager->get($message->getName());

        $processor->process($message);
    }

    /**
     * Process messages while they come in.
     *
     * Note: this function does not terminate.
     */
    public function processAll() : void
    {
        while (true) {
            // step 1: get a message from the top of the queue
            $queueMessage = $this->queueMapper->pop();

            if ($queueMessage === null) {
                // no messages to process, sleep for a little bit
                // we use time_nanosleep, since usleep consumes some cycles while sleeping
                // (at least, on my machine)
                time_nanosleep(0, 500000000);
                continue;
            }

            // step 2: transform into message (currently, all are transformed to PlainMessage)
            $message = new Message(
                $queueMessage->getName(),
                json_decode($queueMessage->getPayload(), JSON_OBJECT_AS_ARRAY)
            );

            // step 3: actually process the message
            $this->process($message);
        }
    }

    /**
     * Push a message onto the queue.
     */
    public function push(Message $message, \DateTime $sheduledAt = null) : void
    {
        // no time specified, schedule immediately
        if ($sheduledAt === null) {
            $sheduledAt = new \DateTime();
        }

        $queueMessage = new QueueMessage();

        $queueMessage->setName($message->getName());
        $queueMessage->setPayload(json_encode($message->getPayload()));
        $queueMessage->setScheduledAt($sheduledAt);

        $this->queueMapper->push($queueMessage);
    }
}
