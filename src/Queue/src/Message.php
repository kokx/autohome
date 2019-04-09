<?php

namespace Queue;

interface Message
{

    /**
     * Obtain the name of this message.
     */
    public function getName() : string;

    /**
     * Obtain the payload of this message.
     */
    public function getPayload();
}
