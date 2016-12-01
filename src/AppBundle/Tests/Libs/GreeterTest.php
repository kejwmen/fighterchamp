<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 29.11.16
 * Time: 13:13
 */

namespace AppBundle\Tests\Libs;


use AppBundle\Libs\Greeter;


class GreeterTest extends \PHPUnit_Framework_TestCase
{
    public function testDisplay()
    {
        $greeter = new Greeter();
        $result = $greeter->display('Mario');
        $expected = 'Mario, you are awesome!';
        $this->assertEquals($expected,$result);
    }
}
