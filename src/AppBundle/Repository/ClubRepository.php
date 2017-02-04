<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 04.02.17
 * Time: 18:53
 */

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;

class ClubRepository extends EntityRepository
{
//    public function findAllOrderByNumberOfUsers()
//    {
//        $qb = $this->createQueryBuilder('club')
//            ->leftJoin('signUpTournament.user', 'user')
//            ->orderBy('')
//        ;
//
//        $query = $qb->getQuery();
//        return $query->execute();
//
//    }

}