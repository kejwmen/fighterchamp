<?php

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