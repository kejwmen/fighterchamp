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

        $sql = "SELECT CONCAT('/ludzie/',us.id) as _self, us.surname, us.name, if(us.male,'M','K') as male
	,TIMESTAMPDIFF(YEAR, us.birth_day, CURDATE()) AS age
  ,sum(case when uf.is_winner is true then 1 else 0 end) as win
  ,sum(case when f.is_draw is true then 1 else 0 end) AS draw
  ,sum(case when uf.is_winner is not true then 1 else 0 end) as lose
	,CONCAT('/kluby', c.id) as club_url, c.name as club_name
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