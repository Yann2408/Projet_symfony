<?php

namespace App\Repository;

use App\Entity\Season;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Season|null find($id, $lockMode = null, $lockVersion = null)
 * @method Season|null findOneBy(array $criteria, array $orderBy = null)
 * @method Season[]    findAll()
 * @method Season[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Season::class);
    }

    public function findSeasonInProgram( $ProgramId): array
    {   
        $query = $this->createQueryBuilder('crea')
            ->join('crea.program', 'prog')
            ->where('prog.id = :ProgramId')
            // ->orderBy('crea.id','ASC')
            ->setParameter('ProgramId', $ProgramId)
            ->getQuery();

        return $query->getResult();
    }

    public function findNumberOfSeason()
    {
        $query = $this->createQueryBuilder('crea')
            ->select('number')
            ->join('crea.program', 'prog')
            ->orderBy('crea.id','DESC')
            ->getQuery();

            return $query->getResult();
    }

    // /**
    //  * @return Season[] Returns an array of Season objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Season
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}