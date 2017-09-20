<?php

use AppBundle\Controller\Admin\AdminTournamentFightController;

/**
 * Created by PhpStorm.
 * User: slk
 * Date: 9/20/17
 * Time: 3:20 PM
 */


class Test extends PHPUnit_Framework_TestCase
{
    /**
     * @var AdminTournamentFightController
     */
    private $controller;

    public function setUp()
    {
        $this->controller = new AdminTournamentFightController();
    }

    /**
     * @dataProvider additionProvider
     */
    public function testGetHighestFormula($signUp0Formula,$signUp1Formula, $expected)
    {
        $signUp = $this->getMockBuilder(\AppBundle\Entity\SignUpTournament::class)
            ->setMethods(['getFormula'])
            ->disableOriginalConstructor()
            ->getMock();

        /**
         * @var $signUp0\AppBundle\Entity\SignUpTournament
         */
        $signUp0 = clone $signUp;

        /**
         * @var $signUp1\AppBundle\Entity\SignUpTournament
         */
        $signUp1 = clone $signUp;

        $signUp0->method('getFormula')->willReturn($signUp0Formula);
        $signUp1->method('getFormula')->willReturn($signUp1Formula);

        $result = $this->controller->getHighestFormula($signUp0, $signUp1);

        $this->assertEquals($result, $expected);
    }

    public function additionProvider()
    {
        return [
            ['A', 'A', 'A'],
            ['A', 'B', 'A'],
            ['A', 'C', 'A'],
            ['B', 'A', 'A'],
            ['B', 'B', 'B'],
            ['B', 'C', 'B'],
            ['C', 'A', 'A'],
            ['C', 'B', 'B'],
            ['C', 'C', 'C'],
        ];
    }

}
