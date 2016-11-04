<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 10.08.16
 * Time: 19:55
 */

namespace AppBundle\Repository;


use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{

    /**
     * @return User()
     */
    public function findAllUserSort()
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin('user.id', 'fc')
            ->addOrderBy('user.name', 'DESC');

        $query = $qb->getQuery();

        return $query->execute();
    }

    /**
     * @return User()
     */
    public function findAllSignUpButNotPairYet()
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin('user.signUpTournament', 'signUpTournament')
            ->andWhere('signUpTournament.user is not null')
            ->leftJoin('user.fights', 'fights' )
            ->andwhere('fights.userOne is null')
            ->leftJoin('user.additionalFights', 'fights2' )
            ->andwhere('fights2.userTwo is null')
            ->andwhere('signUpTournament.ready = 1')
            ->orderBy('user.surname');
        ;

        $query = $qb->getQuery();
        return $query->execute();
    }

    public function showUserFights()
    {
        $qb = $this->createQueryBuilder('user')
        ->leftJoin('user.fights', 'test');



        ;

        $query = $qb->getQuery();
        return $query->execute();

    }



}