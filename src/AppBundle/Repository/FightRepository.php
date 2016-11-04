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

    public function fightAllOrderBy()
    {
        $qb = $this->createQueryBuilder('fight')
            ->addOrderBy('fight.position')
        ;

        $query = $qb->getQuery();
        return $query->execute();

    }

    public function fightReadyOrderBy()
    {
        $qb = $this->createQueryBuilder('fight')
            ->andWhere('fight.ready = :ready')
            ->setParameter('ready', true)
            ->addOrderBy('fight.position')
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function setAllFightsReady()
    {

        $em = $this->getEntityManager();

        $q = $em->createQuery('update AppBundle:Fight fight set fight.ready = ?1 where fight.ready = ?2')
            ->setParameter(1, true)
            ->setParameter(2, false);

        $q->execute();
    }


    public function findAllUserSignUpTournamnet()
    {
        $qb = $this->createQueryBuilder('fight')
            ->leftJoin('fight.UserOne', 'fc');

        $query = $qb->getQuery();
        return $query->execute();
    }

}