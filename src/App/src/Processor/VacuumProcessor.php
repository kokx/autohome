<?php

declare(strict_types=1);

namespace App\Processor;

use Queue\Processor\ProcessorInterface;
use Doctrine\ORM\EntityManager;
use Queue\Message\Message;

class VacuumProcessor implements ProcessorInterface
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
     * Vacuum the SQLite database
     */
    public function process(Message $message) : void
    {
        $connection = $this->em->getConnection();

        $connection->exec("VACUUM");
    }
}
