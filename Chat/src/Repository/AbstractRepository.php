<?php

namespace App\Repository;

use App\Entity\AbstractEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

class AbstractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, string $entity = AbstractEntity::class)
    {
        parent::__construct($registry, $entity);
    }

    public function findItems(array $criteria = [], int $limit = 10, int $page = 1): Paginator
    {
        $queryBuilder = $this->getEntityManager()->createQueryBuilder();
        $expr = $queryBuilder->expr();

        $queryBuilder->select('i');
        $queryBuilder->from($this->getEntityName(), 'i');

        foreach ($criteria as $key => $condition) {
            $queryBuilder->andWhere($expr->eq("i.{$key}", ":{$key}"));
            $queryBuilder->setParameter($key, $condition);
        }

        $queryBuilder->setFirstResult($limit * ($page - 1));
        $queryBuilder->setMaxResults($limit);
        $queryBuilder->orderBy('i.id', 'DESC');

        return new Paginator($queryBuilder->getQuery());
    }
}