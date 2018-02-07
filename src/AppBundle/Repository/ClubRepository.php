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
    public function findAllOrderByNumberOfUsers()
    {
        $conn = $this->getEntityManager()->getConnection();

        $sql = "SELECT CONCAT('/kluby/', c.id) as _self, c.name , count(DISTINCT(us.id)) as user_count
          ,sum(case when uf.result = 'win' then 1 else 0 end) as win
          ,sum(case when uf.result = 'draw' then 1 else 0 end) AS draw
          ,sum(case when uf.result = 'lose' or uf.result = 'disqalify' then 1 else 0 end) as lose
FROM user as us
  LEFT JOIN user_fight AS uf ON uf.user_id = us.id
  LEFT JOIN fight as f ON f.id = uf.fight_id
  JOIN club c ON us.club_id = c.id
GROUP BY c.id";

        $stmt = $conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll();

    }

}