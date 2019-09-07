<?php

namespace Tests\Builder;


use AppBundle\Entity\User;

class UserBuilder extends Builder
{
    public const DEFAULT_NAME = 'DefaultName';
    public const DEFAULT_SURNAME = 'DefaultSurname';
    public const DEFAULT_TYPE = User::TYPE_FIGHTER;

    /**
     * @var string
     */
    private $name = self::DEFAULT_NAME;

    /**
     * @var string
     */
    private $surname = self::DEFAULT_SURNAME;

    /**
     * @var int
     */
    private $type = self::DEFAULT_TYPE;


    public function build(): User
    {
        $user = new User();
        $user->setName($this->name);
        $user->setSurname($this->surname);
        $user->setHash($this->faker->sha1);
        $user->setType($this->type);

        return $user;
    }

    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function withSurname(string $surname): self
    {
        $this->surname = $surname;
        return $this;
    }

    public function withType(int $type): self
    {
        $this->type = $type;
        return $this;
    }
}