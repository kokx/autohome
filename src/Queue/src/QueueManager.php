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
     * @var MessageRepeater[]
     */
    protected $repeaters;

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

        try {
            echo '[' . (new \DateTime())->format('c') . '] ';
            echo 'Processing message with name ' . $message->getName() . "\n";

            $processor->process($message);

            echo '[' . (new \DateTime())->format('c') . '] ';
            echo 'Finished processing message with name ' . $message->getName() . "\n";
        } catch (\Throwable $e) {
            echo '[' . (new \DateTime())->format('c') . "] Exception or Error\n";
            echo "Caught " . get_class($e) . " while executing " . get_class($processor) . "\n";
            echo "Message: " . $e->getMessage() . "\n";
        }
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
                // first check if repeating messages need to be added again
                $this->checkRepeated();

                // no messages to process, sleep for half a second
                usleep(500000);
                continue;
            }

            // step 2: transform into message
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

        echo '[' . (new \DateTime())->format('c') . '] ';
        echo 'Pushed message with name: ' . $message->getName() . "\n";
    }

    /**
     * Push repeated messages if needed.
     */
    protected function checkRepeated()
    {
        foreach ($this->repeaters as $repeater) {
            $message = $repeater->getRepeatMessage();
            if ($message !== null) {
                echo '[' . (new \DateTime())->format('c') . '] ';
                echo 'Added repeating message with name: ' . $message->getName() . "\n";
                $this->push($message);
            }
        }
    }

    /**
     * Add a repeated message.
     */
    public function addRepeater(MessageRepeater $messageRepeater)
    {
        $this->repeaters[] = $messageRepeater;
    }
}
