<?php

namespace Queue;

use Queue\Entity\QueueMessage;
use Queue\Mapper\QueueMapper;
use Queue\Message\Message;
use Queue\Processor\ProcessorManager;
use Psr\Log\LoggerInterface;

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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var MessageRepeater[]
     */
    protected $repeaters;

    /**
     * @param QueueMapper $mapper
     * @param ProcessorManager $processorManager
     * @param Logger $logger
     */
    public function __construct(QueueMapper $mapper, ProcessorManager $processorManager, LoggerInterface $logger)
    {
        $this->queueMapper = $mapper;
        $this->processorManager = $processorManager;
        $this->logger = $logger;
    }

    /**
     * Process a single message.
     */
    public function process(Message $message) : void
    {
        $processor = $this->processorManager->get($message->getName());

        try {
            $this->logger->info('Processing message with name ' . $message->getName());

            $processor->process($message);

            $this->logger->info('Finished processing message with name ' . $message->getName());
        } catch (\Throwable $e) {
            $this->logger->error("Exception or error --  Caught " . get_class($e)
                                 . " while executing " . get_class($processor) . ' -- Message: ' . $e->getMessage(), ['exception' => $e]);
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

        $this->logger->info('Pushed message with name: ' . $message->getName());
    }

    /**
     * Push repeated messages if needed.
     */
    protected function checkRepeated()
    {
        foreach ($this->repeaters as $repeater) {
            $message = $repeater->getRepeatMessage();
            if ($message !== null) {
                $this->logger->info('Added repeating message with name: ' . $message->getName());
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
