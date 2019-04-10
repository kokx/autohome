<?php

namespace Queue\Message;

class Message
{

    /**
     * Message name.
     * @var string
     */
    protected $name;

    /**
     * Message payload.
     * @var mixed
     */
    protected $payload;

    /**
     * Message constructor.
     * @param $name
     * @param $payload
     */
    public function __construct($name, $payload)
    {
        $this->name = $name;
        $this->payload = $payload;
    }

    /**
     * Obtain the name of this message.
     */
    public function getName() : string
    {
        return $this->name;
    }

    /**
     * Obtain the payload of this message.
     *
     * Will be serialized by the queue.
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
