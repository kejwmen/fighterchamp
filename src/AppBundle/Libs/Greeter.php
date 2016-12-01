<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 29.11.16
 * Time: 13:10
 */

namespace AppBundle\Libs;


class Greeter
{
        public function display($name)
        {
            return $name . ", you are awesome!";
        }
}