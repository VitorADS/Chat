<?php

namespace App\Repository;

use App\Entity\Person;
use Doctrine\Persistence\ManagerRegistry;

class PersonRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Person::class);
    }
}
