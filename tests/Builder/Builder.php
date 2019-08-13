<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 7/26/19
 * Time: 10:53 AM
 */

namespace Tests\Builder;

use Faker;

class Builder
{
    /**
     * @var Faker\Generator
     */
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }
}