<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 17.08.16
 * Time: 12:14
 */

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;


class RulesetRepository extends EntityRepository
{

    public function getWeight()
    {

     $dql = 'SELECT rule FROM AppBundle\Entity\Ruleset rule';

        $query = $this->getEntityManager()->createQuery($dql);
        return $query->execute();
    }


}