<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 3/26/18
 * Time: 5:32 PM
 */

namespace Tests\AppBundle;


use PHPUnit\Framework\TestCase;

class Argument
{
    public function __construct(string $boo, string $moo)
    {

    }
}

class ArgumentTest extends TestCase
{
    public function testArg()
    {
        $arg = new Argument('ssdf', null);
    }
}
