<?php

namespace Queue\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="queue")
 */
class Queue
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @var int
     */
    protected $id;

    /**
     * Message name.
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * Message payload.
     * @ORM\Column(type="text")
     * @var string
     */
    protected $payload;

    /**
     * Scheduled date. This message will not be sent before this date.
     * @ORM\Column(name="scheduled_at", type="datetime")
     * @var \DateTime
     */
    protected $scheduledAt;

    /**
     * Created on.
     * @ORM\Column(name="created_on", type="datetime")
     * @var \DateTime
     */
    protected $createdOn;

    /**
     * Queue constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->createdOn = new \DateTime();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @param string $payload
     */
    public function setPayload(string $payload): void
    {
        $this->payload = $payload;
    }

    /**
     * @return \DateTime
     */
    public function getScheduledAt(): \DateTime
    {
        return $this->scheduledAt;
    }

    /**
     * @param \DateTime $scheduledAt
     */
    public function setScheduledAt(\DateTime $scheduledAt): void
    {
        $this->scheduledAt = $scheduledAt;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedOn(): \DateTime
    {
        return $this->createdOn;
    }
}
