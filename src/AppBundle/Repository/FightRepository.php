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
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class FightRepository extends EntityRepository
{

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
            ->andWhere('fight.isReady = :ready')
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

        $q = $em->createQuery('update AppBundle:Fight fight set fight.is_ready = ?1 where fight.is_ready = ?2 and fight.tournament = ?3')
            ->setParameter(1, true)
            ->setParameter(2, false)
            ->setParameter(3, $tournament);

        $q->execute();
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

    public function findAllArray()
    {
        $conn = $this->getEntityManager()
            ->getConnection();



        $sql = 'SELECT us.surname, us.name, if(us.male,\'M\',\'K\') as male, uf.is_winner, if(f.is_draw,TRUE, FALSE) as is_draw, uf.is_disqualified, uf.is_red_corner
	,TIMESTAMPDIFF(YEAR, us.birth_day, CURDATE()) AS age,CONCAT(\'/kluby/\', c.id) club_url,CONCAT(\'/walki/\', f.id) as _self, CONCAT(\'/ludzie/\',us.id) user_url, f.youtube_id, c.name as club_name,
(select count(*) FROM user_fight WHERE uf.user_id = user_fight.user_id and user_fight.is_winner is true) as win,
(select count(*) FROM user_fight JOIN fight f1 ON user_fight.fight_id = f1.id WHERE uf.user_id = user_fight.user_id and user_fight.is_winner is not true and f1.is_draw is false) as lose,
(select count(*) FROM user_fight JOIN fight f2 ON user_fight.fight_id = f2.id WHERE uf.user_id = user_fight.user_id and f2.is_draw is true) as draw
   FROM user_fight uf
   JOIN user us ON uf.user_id = us.id
   LEFT JOIN club c ON us.club_id = c.id
   JOIN fight f ON uf.fight_id = f.id
   GROUP BY uf.id
   ORDER BY f.id
';
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}
