<?php

namespace App\Repository;

use App\Entity\Pub;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Pub|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pub|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pub[]    findAll()
 * @method Pub[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PubRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Pub::class);
    }

    /**
     * @return Pub[] Returns an array of Pub objects
     */

    public function findByNameCoucou($name)
    {
        $pubs = $this->getEntityManager()->createQueryBuilder()
            ->select('p.name, 1 AS coucou')
            ->from(Pub::class, 'p')
            ->orderBy('coucou')
            ->getQuery()
            ->getResult();

        dump($pubs);

        return ($qb = $this->createQueryBuilder('p'))
            ->andWhere('p.name = :name')
            ->andWhere($qb->expr()->eq(1, 1))
            ->setParameter('name', $name)
            ->orderBy('p.name', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Pub
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
