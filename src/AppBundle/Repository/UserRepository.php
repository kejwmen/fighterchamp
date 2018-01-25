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

    public function findAllByType()
    {
        $conn = $this->getEntityManager()
            ->getConnection();

        $sql = "SELECT us.id, us.surname, us.name, if(us.male,'M','K') as male
	,TIMESTAMPDIFF(YEAR, us.birth_day, CURDATE()) AS age
	,IFNULL(sum(case when f.winner_id = us.id then 1 end), 0) as win
	,sum(case when f.draw then 1 else 0 end) AS draw
	,IFNULL(sum(case when f.winner_id != user_id and !f.draw then 1 end), 0) as lose
	,c.id as club_id, c.name as club_name
FROM user as us
	LEFT JOIN user_fight AS uf ON uf.user_id = us.id
	LEFT JOIN fight as f ON f.id = uf.fight_id
	LEFT JOIN club c ON us.club_id = c.id
group by us.id";



        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();
    }

}