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



    public function findAllSignUpsPaidButDeleted($tournament)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->andWhere('signUpTournament.deleted_at is not null')
            ->andWhere('signUpTournament.isPaid = true')
            ->setParameter('tournament', $tournament);

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllSignUpsPaid($tournament)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->andWhere('signUpTournament.deleted_at is null')
            ->andWhere('signUpTournament.isPaid = true')
            ->setParameter('tournament', $tournament)
            ->addOrderBy('user.surname')
    ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllSignUpsPaidAndWeightedOrder($tournament)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->andWhere('signUpTournament.deleted_at is null')
            ->andWhere('signUpTournament.isPaid = true')
            ->andWhere('signUpTournament.fights is empty' )
            ->setParameter('tournament', $tournament)
            ->addOrderBy('signUpTournament.weighted')
            ->addOrderBy('user.surname')
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }




    public function signUpUserOrder($tournament)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->addSelect('user')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->andWhere('signUpTournament.deleted_at is null')
            ->setParameter('tournament', $tournament)
            ->addOrderBy('user.male')
            ->addOrderBy('signUpTournament.formula')
            ->addOrderBy('signUpTournament.weight');

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllSignUpButNotPairYetQB($tournament)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->andWhere('signUpTournament.fights is empty' )
            ->andWhere('signUpTournament.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->andWhere('signUpTournament.isPaid = true')
//            ->andWhere('signUpTournament.weighted is not null')
            ->addOrderBy('user.male')
            ->addOrderBy('signUpTournament.formula')
            ->addOrderBy('signUpTournament.weight')
            ->addOrderBy('signUpTournament.trainingTime')
            ->addOrderBy('user.birthDay', 'DESC')
            ->addOrderBy('user.surname')
        ;

//        $query = $qb->getQuery();
//        return $query->execute();

        return $qb;
    }


    public function findAllSignUpButNotPairYet($tournament)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->andWhere('signUpTournament.fights is empty' )
            ->andWhere('signUpTournament.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->andWhere('signUpTournament.isPaid = true')
//            ->andWhere('signUpTournament.weighted is not null')
            ->addOrderBy('user.male')
            ->addOrderBy('signUpTournament.formula')
            ->addOrderBy('signUpTournament.weight')
            ->addOrderBy('signUpTournament.trainingTime')
            ->addOrderBy('user.birthDay', 'DESC')
            ->addOrderBy('user.surname')
        ;


        $query = $qb->getQuery();
        return $query->execute();


    }





    public function findAllSortByMaleClassWeightSurname($tournament)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->andWhere('signUpTournament.deleted_at is null')
            ->setParameter('tournament', $tournament)
            ->addSelect('user')
            ->addOrderBy('user.male')
            ->addOrderBy('signUpTournament.formula')
            ->addOrderBy('signUpTournament.weight')
            ->addOrderBy('user.surname')

        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function findAllSortByReady($tournament)
    {
        $qb = $this->createQueryBuilder('signUpTournament')
            ->leftJoin('signUpTournament.user', 'user')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->andWhere('signUpTournament.deleted_at is null')
            ->setParameter('tournament', $tournament)
            ->addSelect('user')
            ->addOrderBy('signUpTournament.weighted')
            ->addOrderBy('user.surname')
        ;

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