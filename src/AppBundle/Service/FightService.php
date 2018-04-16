<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 4/16/18
 * Time: 10:13 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Fight;

class FightService
{
    public function toggleCorners(Fight $fight): void
    {
        $usersFight = $fight->getUsersFight();

        $userOneFight = $usersFight[0];
        $userTwoFight = $usersFight[1];

        $one = $userOneFight->isRedCorner();
        $two = $userTwoFight->isRedCorner();

        $this->convertNullToFalse($one);
        $this->convertNullToFalse($two);

        if($one === $two){
            $one = true;
            $two = false;
        }

        $one = ($one === true) ? false : true;
        $two = ($two === false) ? true : false;

        $userOneFight->setIsRedCorner($one);
        $userTwoFight->setIsRedCorner($two);
    }

    private function convertNullToFalse(&$arg)
    {
        $arg = $arg ?? false;
    }
}