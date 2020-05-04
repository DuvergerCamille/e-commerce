<?php

namespace App\Repository;

use App\Entity\CommandeProduit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommandeProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeProduit[]    findAll()
 * @method CommandeProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeProduitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeProduit::class);
    }

    public function getArticlesInCommande($id)
    {
        $qb = $this->createQueryBuilder('cp');

        $qb->innerJoin('cp.commande', 'c')->addSelect('c');

        $qb->andWhere('c.id = :id')->setParameter('id', $id);

        return $qb->getQuery()->getResult();
    }
}
