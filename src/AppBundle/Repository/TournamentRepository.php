<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 21.12.16
 * Time: 22:55
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Tournament;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository;

class TournamentRepository extends EntityRepository
{
    public static function createFightsReadyCriteria()
    {
        return Criteria::create()
        ->where(Criteria::expr()->eq('isVisible', true))
        ->orderBy(['day' => 'ASC'])
        ->orderBy(['position' => 'ASC']);
    }

    public static function createSignsUpTournamentNotDeleted()
    {
        return Criteria::create()
        ->where(Criteria::expr()->eq('deleted_at', null));
    }
}