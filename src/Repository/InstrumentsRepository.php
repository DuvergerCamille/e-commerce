<?php

namespace App\Repository;

use App\Entity\Instruments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Instruments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instruments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instruments[]    findAll()
 * @method Instruments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstrumentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instruments::class);
    }

    public function getInstrumentsInCategory(string $category)
    {
        $qb = $this->createQueryBuilder('i');

        $qb->innerJoin('i.categories', 'c')->addSelect('c');

        $qb->andWhere('c.nom = :category')->setParameter('category', $category);

        return $qb->getQuery()->getResult();
    }
}