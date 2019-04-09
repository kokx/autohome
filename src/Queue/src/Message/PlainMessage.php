<?php

namespace Queue\Message;

use Queue\Message;

class PlainMessage implements Message
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $payload;

    /**
     * PlainMessage constructor.
     */
    public function __construct(string $name, $payload)
    {
        $this->name = $name;
        $this->payload = $payload;
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function getPayload()
    {
        return $this->payload;
    }
}
