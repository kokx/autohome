<?php

namespace Queue\Mapper;

use Doctrine\ORM\EntityManager;
use Queue\Entity\QueueMessage;

/**
 * Class QueueMapper
 *
 * This is the queue manager
 * @package Queue\Mapper
 */
class QueueMapper
{

    /**
     * @var EntityManager
     */
    protected $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Pop the first message from the queue, if it is not empty.
     * @return QueueMessage|null
     */
    public function pop() : ?QueueMessage
    {
        $dql = "SELECT m FROM Queue\Entity\QueueMessage m
                    WHERE m.scheduledAt <= CURRENT_TIMESTAMP()
                    ORDER BY m.createdOn ASC";

        $query = $this->em->createQuery($dql);
        $query->setMaxResults(1);

        $query->execute();

        $message = $query->getOneOrNullResult();

        if ($message !== null) {
            $this->em->remove($message);
            $this->em->flush();
        }

        return $message;
    }

    /**
     * Push a message onto the queue.
     */
    public function push(QueueMessage $message)
    {
        $this->em->persist($message);
        $this->em->flush();
    }
}
