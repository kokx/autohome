<?php

namespace Queue;

use Queue\Message\Message;

/**
 * Repeat a message at will.
 */
class MessageRepeater
{

    /**
     * @var Message
     */
    protected $message;

    /**
     * @var \DateInterval
     */
    protected $interval;

    /**
     * The next time this message should be added.
     * @var \DateTime
     */
    protected $next;

    /**
     * MessageRepeater constructor.
     * @param Message $message
     * @param \DateInterval $interval
     */
    public function __construct(Message $message, \DateInterval $interval)
    {
        $this->message = $message;
        $this->interval = $interval;
    }

    /**
     * Returns a message if it should be repeated, null otherwise.
     * @return Message|null
     */
    public function getRepeatMessage() : ?Message
    {
        if (!$this->checkRepeat()) {
            return null;
        }

        $this->next = (new \DateTime())->add($this->interval);

        return $this->message;
    }

    /**
     * Check if the message should be repeated.
     * @return bool
     */
    public function checkRepeat() : bool
    {
        return $this->next === null || $this->next < new \DateTime();
    }
}
