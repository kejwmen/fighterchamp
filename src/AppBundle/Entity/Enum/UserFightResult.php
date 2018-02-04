<?php

namespace AppBundle\Entity\Enum;
use MyCLabs\Enum\Enum;


/**
 * @method static UserFightResult WIN()
 * @method static UserFightResult DRAW()
 * @method static UserFightResult LOSE()
 * @method static UserFightResult DISQUALIFIED()
 */
class UserFightResult extends Enum
{
    const WIN = 'win';
    const DRAW = 'draw';
    const LOSE = 'lose';
    const DISQUALIFY = 'disqualify';
}