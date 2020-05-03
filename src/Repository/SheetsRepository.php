<?php

namespace App\Repository;

use App\Entity\Sheets;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sheets|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sheets|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sheets[]    findAll()
 * @method Sheets[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SheetsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sheets::class);
    }

    public function getSheetsInCategory(string $category)
    {
        $qb = $this->createQueryBuilder('s');

        $qb->innerJoin('s.categories', 'c')->addSelect('c');

        $qb->andWhere('c.nom = :category')->setParameter('category', $category);

        return $qb->getQuery()->getResult();
    }
}
