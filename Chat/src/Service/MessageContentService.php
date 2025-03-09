<?php

namespace App\Service;

use App\Entity\MessageContent;
use Doctrine\ORM\EntityManagerInterface;

class MessageContentService extends AbstractService
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager, MessageContent::class);
    }
}