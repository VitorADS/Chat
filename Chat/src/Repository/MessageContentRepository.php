<?php

namespace App\Repository;

use App\Entity\MessageContent;
use Doctrine\Persistence\ManagerRegistry;

class MessageContentRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MessageContent::class);
    }
}
