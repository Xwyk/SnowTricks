<?php

namespace App\Service;

use App\Entity\Category;
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

    public function getLastFigures(Category $category = null): iterable{
        $qb = $this->figureRepo->createQueryBuilder('f')
            ->orderBy('f.creationDate', 'DESC')
            ->setMaxResults(self::FIGURES_LIMIT_PER_QUERY);
        if ($category){
            $qb->where('f.category = :category')
                ->setParameter('category', $category);
        }
        return $qb->getQuery()
                  ->getResult();
    }

    public function getNextFigures(int $start, Category $category = null): iterable{
        $qb = $this->figureRepo->createQueryBuilder('f')
            ->orderBy('f.creationDate', 'DESC')
            ->setMaxResults(self::FIGURES_LIMIT_PER_QUERY)
            ->setFirstResult($start);
        if ($category){
            $qb->where('f.category = :category')
                ->setParameter('category', $category);
        }
        return $qb->getQuery()
                  ->getResult();
    }

    public function getCategories(){
        return  $this->categoryRepo->createQueryBuilder('c')
            ->orderBy('c.name', 'DESC')
            ->getQuery()
            ->getResult();
    }
}