<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 06.09.16
 * Time: 12:57
 */

namespace AppBundle\Service;


class StandUp
{
    public function parse($str)
    {
        return strtoupper($str);
    }
}