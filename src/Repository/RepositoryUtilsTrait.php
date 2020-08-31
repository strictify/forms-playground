<?php

declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\Common\Collections\ExpressionBuilder;

/**
 * @template T
 *
 * @method EntityManager getEntityManager()
 *
 */
trait RepositoryUtilsTrait
{
    /**
     * @psalm-param T $entity
     *
     * @psalm-return T
     *
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    private function persistEntity($entity)
    {
        $this->getEntityManager()->persist($entity);

        return $entity;
    }

    /**
     * @psalm-param T $entity
     *
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    public function removeEntity($entity): void
    {
        $this->getEntityManager()->remove($entity);
    }

    public function flush(): void
    {
        $this->getEntityManager()->flush();
    }

    /**
     * @psalm-param T $entity
     *
     * @psalm-suppress MixedArgumentTypeCoercion
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function save(object $entity, bool $flush = true): void
    {
        $em = $this->getEntityManager();
        $em->persist($entity);
        if ($flush) {
            $em->flush();
        }
    }

    /**
     * @psalm-return array<T>
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getResults(Expression ...$expressions): array
    {
        $criteria = $this->createCriteria(...$expressions);

        return $this->matching($criteria)->toArray();
    }

    /**
     * @psalm-return T|null
     * @psalm-suppress PossiblyUnusedMethod
     */
    public function getOneResultOrNull(Expression ...$expressions)
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
