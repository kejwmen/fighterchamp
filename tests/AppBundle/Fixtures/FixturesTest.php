<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 1/14/18
 * Time: 10:47 AM
 */


class FixturesTest extends PHPUnit_Framework_TestCase
{
    public function num (int $number)
    {
        if($number === 1){
           return $number;
        }

        return $number - 1;
    }

    public function testFixtures()
    {
       $result =[];
        foreach(range(1,20) as $number)
        {
            $userFight = new stdClass();
            $userFight->fight []= $this->num($number);
            $userFight->user []= $number;

            $result []= $userFight;
        }

        $this->assertEquals(1, $result[0]->fight[0]);
        $this->assertEquals(1, $result[0]->user[0]);

        $this->assertEquals(1, $result[1]->fight[0]);
        $this->assertEquals(2, $result[1]->user[0]);

        $this->assertEquals(2, $result[2]->fight[0]);
        $this->assertEquals(3, $result[2]->user[0]);

        $this->assertEquals(2, $result[2]->fight[0]);
        $this->assertEquals(4, $result[2]->user[0]);
    }

}
