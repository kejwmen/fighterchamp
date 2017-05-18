<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 16.08.16
 * Time: 15:20
 */

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class FightRepository extends EntityRepository
{

    public function fightAllOrderBy($tournament)
    {
        $qb = $this->createQueryBuilder('fight')
            ->addOrderBy('fight.day', 'DESC')
            ->addOrderBy('fight.position')
            ->andWhere('fight.tournament = :tournament')
            ->setParameter('tournament', $tournament)
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

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


    public function findAllFightsForUser($user)
    {
        $qb = $this->createQueryBuilder('fight')
            ->leftJoin('fight.signuptournament', 'signuptournament')
            ->andWhere('signuptournament.user = :user')
            ->setParameter('user', $user);

        $query = $qb->getQuery();
        return $query->execute();
    }

}