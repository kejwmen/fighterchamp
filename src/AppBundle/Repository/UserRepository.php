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

    public function findAllUserWithFight()
    {
        $qb = $this->createQueryBuilder('user')
            ->leftJoin('user.signUpTournament', 'signuptournament')
            ->leftJoin('signuptournament.fights', 'fights');

//            ->andWhere('signuptournament.user = :user')
//            ->setParameter('user', $user);

        $query = $qb->getQuery();

        return $query->execute();
    }


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


    public function findAllSignUpButNotPairYet($tournament)
    {
        $qb = $this->createQueryBuilder('user')
          //  ->select('user, signUpTournament.formula')
            ->leftJoin('user.signUpTournament', 'signUpTournament')
            ->andWhere('signUpTournament.user is not null')
            ->andWhere('signUpTournament.ready = 1')
            ->andWhere('signUpTournament.tournament = :tournament')
            ->setParameter('tournament', $tournament)
            ->andWhere('user.fights is empty' )
            ->addOrderBy('user.male')
            ->addOrderBy('signUpTournament.formula')
            ->addOrderBy('signUpTournament.weight')

        ;

      //  $query = $qb->getQuery();
      //  return $query->execute();

        return $qb;
    }



}