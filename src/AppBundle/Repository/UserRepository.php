<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 10.08.16
 * Time: 19:55
 */

namespace AppBundle\Repository;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public static function createCoachCriteria()
    {
        return Criteria::create()
            ->where(Criteria::expr()->eq('type', 2));
    }


    public function findAllNotSignUpForTournament($tournament)
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin('user.signUpTournaments', 'signUpTournament')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->setParameter('tournament', $tournament);

        $query = $qb->getQuery();
        return $query->execute();
    }

}