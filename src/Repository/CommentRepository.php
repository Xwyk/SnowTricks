<?php

namespace App\Repository;

use App\Entity\Comment;
use App\Entity\Figure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Comment|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comment|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comment[]    findAll()
 * @method Comment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentRepository extends ServiceEntityRepository
{

    const COMMENTS_LIMIT_PER_QUERY = 5;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Comment::class);
    }

    public function getLastCommentsForFigure(Figure $figure): iterable{

        return  $this->createQueryBuilder('c')
            ->where('c.figure = :figure')
            ->setParameter('figure', $figure)
            ->orderBy('c.creationDate', 'DESC')
            ->setMaxResults(self::COMMENTS_LIMIT_PER_QUERY)
            ->getQuery()
            ->getResult();
    }

    public function getNextCommmentsForFigure(Figure $figure, int $start): iterable
    {
        return  $this->createQueryBuilder('c')
            ->where('c.figure = :figure')
            ->setParameter('figure', $figure)
            ->orderBy('c.creationDate', 'DESC')
            ->setMaxResults(self::COMMENTS_LIMIT_PER_QUERY)
            ->setFirstResult($start)
            ->getQuery()
            ->getResult();
    }
}
