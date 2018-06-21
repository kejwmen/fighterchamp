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

    public function findAllListAction(int $type)
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin('user.userFights', 'userFights')
            ->leftJoin('userFights.fight', 'fights')
            ->leftJoin('userFights.awards', 'awards')
            ->leftJoin('user.club', 'club')
            ->leftJoin('fights.tournament', 'tournament')
            ->addSelect('fights')
            ->addSelect('userFights')
            ->addSelect('awards')
            ->addSelect('club')
            ->andWhere('user.type = :type')
            ->setParameter('type', $type)
//            ->setCacheable(true)
//            ->setMaxResults(10)
        ;


        $query = $qb->getQuery();
        return $query->execute();
    }

}