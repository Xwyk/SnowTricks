<?php

namespace App\Service;

use App\Entity\Figure;
use App\Repository\CategoryRepository;
use App\Repository\CommentRepository;
use App\Repository\FigureRepository;
use App\Repository\UserRepository;

class DBQueries{

    const COMMENTS_LIMIT_PER_QUERY = 5;
    const FIGURES_LIMIT_PER_QUERY = 12;

    private $commentRepo;
    private $userRepo;
    private $figureRepo;
    private $categoryRepo;

    public function __construct(CommentRepository $commentRepo, UserRepository $userRepo, FigureRepository $figureRepo, CategoryRepository $categoryRepo)
    {
        $this->userRepo = $userRepo;
        $this->commentRepo = $commentRepo;
        $this->figureRepo = $figureRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function getLastCommentsForFigure(Figure $figure): iterable{

        return  $this->commentRepo->createQueryBuilder('c')
            ->where('c.figure = :figure')
            ->setParameter('figure', $figure)
            ->orderBy('c.creationDate', 'DESC')
            ->setMaxResults(self::COMMENTS_LIMIT_PER_QUERY)
            ->getQuery()
            ->getResult();
    }

    public function getNextCommmentsForFigure(Figure $figure, int $start): iterable
    {
        return  $this->commentRepo->createQueryBuilder('c')
            ->where('c.figure = :figure')
            ->setParameter('figure', $figure)
            ->orderBy('c.creationDate', 'DESC')
            ->setMaxResults(self::COMMENTS_LIMIT_PER_QUERY)
            ->setFirstResult($start)
            ->getQuery()
            ->getResult();
    }

    public function getLastFigures(): iterable{
        return $this->figureRepo->createQueryBuilder('f')
            ->orderBy('f.creationDate', 'DESC')
            ->setMaxResults(self::FIGURES_LIMIT_PER_QUERY)
            ->getQuery()
            ->getResult();
    }

    public function getNextFigures(int $start): iterable{
        return $this->figureRepo->createQueryBuilder('f')
            ->orderBy('f.creationDate', 'DESC')
            ->setMaxResults(self::FIGURES_LIMIT_PER_QUERY)
            ->setFirstResult($start)
            ->getQuery()
            ->getResult();
    }
}