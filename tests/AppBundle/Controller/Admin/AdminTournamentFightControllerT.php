<?php

use AppBundle\Controller\Admin\AdminTournamentFightController;
use AppBundle\Entity\UserFight;
use PHPUnit\Framework\TestCase;

class AdminTournamentFightControllerT extends TestCase
{
    /**
     * @var AdminTournamentFightControllerT
     */
    private $controller;

    public function setUp()
    {
        $this->controller = new AdminTournamentFightControllerT();
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


    public function additionProviderCorners()
    {
        return [
            [null, null],
            [null, false],
            [null, true],

            [true, null],
            [true, false],
            [true, true],

            [false, null],
            [false, false],
            [false, true],
        ];
    }


    /**
     * @dataProvider additionProviderCorners
     */
    public function testToggleCorners(?bool $userOneCorner, ?bool $userTwoCorner)
    {
       $userOneFight = new UserFight();
       $userOneFight->setIsRedCorner($userOneCorner);

       $userTwoFight = new UserFight();
       $userTwoFight->setIsRedCorner($userTwoCorner);

       $this->toggleCorners($userOneFight, $userTwoFight);

       $this->assertNotSame($userOneFight->isRedCorner(), $userTwoFight->IsRedCorner());

    }

    public function toggleCorners(UserFight $userOneFight, UserFight $userTwoFight): void
    {
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

    public function convertNullToFalse(&$arg)
    {
        $arg = $arg ?? false;
    }
}
