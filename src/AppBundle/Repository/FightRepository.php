<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 16.08.16
 * Time: 15:20
 */

namespace AppBundle\Repository;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\Tournament;
use Doctrine\ORM\EntityRepository;

class FightRepository extends EntityRepository
{


    public function findAllFightsForTournament($tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->addOrderBy('fight.day')
            ->addOrderBy('fight.position')
            ->andWhere('fight.tournament = :tournament')
            ->andWhere('fight.ready = 1')
            ->setParameter('tournament', $tournament)

        ;
        $query = $qb->getQuery();
        return $query->execute();

    }

    public function findAllFightsForTournamentAdmin($tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->addOrderBy('fight.day')
            ->addOrderBy('fight.position')
            ->andWhere('fight.tournament = :tournament')
            ->setParameter('tournament', $tournament)

        ;
        $query = $qb->getQuery();
        return $query->execute();

    }




    public function findAllFightByDayAdmin($tournament,$day)
    {
        $qb = $this->createQueryBuilder('fight')
            ->addOrderBy('fight.position')
            ->andWhere('fight.tournament = :tournament')
            ->andWhere('fight.day = :day')
            ->setParameter('tournament', $tournament)
            ->setParameter('day', $day)
        ;

        $query = $qb->getQuery();
        return $query->execute();

    }

    public function fightAllInDayOrderBy($tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            //->addOrderBy('fight.day', 'DESC')
            ->addOrderBy('fight.position')
            ->andWhere('fight.tournament = :tournament')
            //->andWhere('fight.day = :day')
            ->setParameter('tournament', $tournament)
            //->setParameter('day', $day)
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }



    public function fightReadyOrderBy($tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->andWhere('fight.ready = :ready')
            ->andWhere('fight.tournament = :tournament')
            ->setParameter('ready', true)
            ->setParameter('tournament', $tournament)
            ->addOrderBy('fight.position')
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function setAllFightsReady($tournament)
    {

        $em = $this->getEntityManager();

        $q = $em->createQuery('update AppBundle:Fight fight set fight.ready = ?1 where fight.ready = ?2 and fight.tournament = ?3')
            ->setParameter(1, true)
            ->setParameter(2, false)
            ->setParameter(3, $tournament);

        $q->execute();
    }


    //should be plural
    public function findUserFightInTournament(SignUpTournament $signUp, Tournament $tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->leftJoin('fight.signuptournament', 'signuptournament')
            ->andWhere('signuptournament = :signUp')
            ->setParameter('signUp', $signUp);

        $query = $qb->getQuery();
        return $query->execute();
    }



    public function findAllTournamentFightsWhereFightersAreNotWeighted(Tournament $tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->leftJoin('fight.signuptournament', 'signuptournament')
            ->andWhere('signuptournament.tournament = :tournament')
            ->andWhere('signuptournament.weighted is null')
            ->setParameter('tournament', $tournament);

        $query = $qb->getQuery();
        return $query->execute();
    }

}