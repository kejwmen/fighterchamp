<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 11.08.16
 * Time: 11:41
 */

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class SignUpTournamentRepository extends EntityRepository
{

    public function signUpUserOrder()
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->addSelect('user')
            ->addOrderBy('user.male')
            ->addOrderBy('signUpTournament.formula')
            ->addOrderBy('signUpTournament.weight');

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllSignUpButNotPairYet()
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->andWhere('user.fights is empty' )
            ->andwhere('signUpTournament.ready = 1')
            ->addOrderBy('user.male')
            ->addOrderBy('signUpTournament.formula')
            ->addOrderBy('signUpTournament.weight');
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllSortByReady()
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->addSelect('user')
            ->addOrderBy('signUpTournament.ready')
            ->addOrderBy('user.surname');

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findOpponent($male, $weight, $formula)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->leftJoin('user.fights', 'fights' )
            ->andWhere('user.male = :male')
            ->andWhere('signUpTournament.weight = :weight')
            ->andWhere('signUpTournament.formula = :formula')
            ->setParameter('male', $male)
            ->setParameter('weight', $weight)
            ->setParameter('formula', $formula)

        ;

        $query = $qb->getQuery();
      //  $query->getArrayResult();

        return $query->execute();
    }


}