<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\Common\Collections\ExpressionBuilder;

/**
 * @method EntityManager getEntityManager()
 */
trait RepositoryUtilsTrait
{
//    public function persist(object $entity): void
//    {
//        $this->getEntityManager()->persist($entity);
//    }
//
//    public function remove(object $entity): void
//    {
//        $this->getEntityManager()->remove($entity);
//    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    public function save(object $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }

    }
    public function matchBy(Expression ...$expressions): array
    {
        $criteria = $this->createCriteria(...$expressions);

        return $this->matching($criteria)->toArray();
    }

    public function matchOneBy(Expression ...$expressions): ?object
    {
        $criteria = $this->createCriteria(...$expressions);

        return $this->matching($criteria)->first();
    }

    public function expr(): ExpressionBuilder
    {
        return Criteria::expr();
    }

    private function createCriteria(Expression ...$expressions): Criteria
    {
        $criteria = Criteria::create();
        foreach ($expressions as $expression) {
            $criteria->andWhere($expression);
        }

        return $criteria;
    }

}
