<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 2/4/18
 * Time: 1:27 PM
 */

namespace AppBundle\Tests;

use AppBundle\Entity\Enum\UserFightResult;
use AppBundle\Entity\UserFight;


class UserFightTest extends \PHPUnit_Framework_TestCase
{
    public function testOne()
    {
        $userFight = new UserFight();

        $userFightResult = UserFightResult::WIN();

//        var_dump($userFightResult);

        $userFight->setResult(UserFightResult::DRAW());

        var_dump($userFight->getResult()->getValue());

    }
}
