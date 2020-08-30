<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Movie;
use App\Entity\FavoriteMovie;
use Doctrine\Common\Collections\Expr\Expression;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FavoriteMovie|null find($id, $lockMode = null, $lockVersion = null)
 * @method FavoriteMovie|null findOneBy(array $criteria, array $orderBy = null)
 * @method FavoriteMovie[]    findAll()
 * @method FavoriteMovie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FavoriteMovieRepository extends ServiceEntityRepository
{
    /** @use RepositoryUtilsTrait<FavoriteMovie> */
    use RepositoryUtilsTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FavoriteMovie::class);
    }

    public function removeFor(User $user, Movie $movie): void
    {
        if ($favMovie = $this->findOneBy(['user' => $user, 'movie' => $movie])) {
            $this->getEntityManager()->remove($favMovie);
        }
    }

    public function createFor(User $user, Movie $movie): FavoriteMovie
    {
        $favMovie = new FavoriteMovie($user, $movie);
        $this->getEntityManager()->persist($favMovie);

        return $favMovie;
    }

    public function whereUser(User $user): Expression
    {
        return $this->expr()->eq('user', $user);
    }
}
