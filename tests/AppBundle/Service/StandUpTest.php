<?php

use PHPUnit\Framework\TestCase;

class StandUpTest extends TestCase
{
    public function testStandUp()
    {
        $mock = $this->getMockBuilder(\AppBundle\Service\StandUp::class)
            ->setMethodsExcept(['parse'])
            ->getMock();


        $parsedMock = $mock->parse('tutaj mamy problem');

        var_dump($parsedMock);


        $standUp = new \AppBundle\Service\StandUp();
        $parsed = $standUp->parse('mariusz poszedl do domu');

        var_dump($parsed);



    }
}
