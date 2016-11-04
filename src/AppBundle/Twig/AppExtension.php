<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 08.08.16
 * Time: 12:57
 */

namespace AppBundle\Twig;
use Twig_Extension;

class AppExtension extends Twig_Extension
{
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('getAge', array($this, 'getAgefilter')),
        );
    }

    public function getAgeFilter($date)
    {
        if (!$date instanceof \DateTime) {
            // turn $date into a valid \DateTime object or let return
            return null;
        }

        //$referenceDate = date('01-01-Y');
        $referenceDateTimeObject = new \DateTime('now');

        $diff = $referenceDateTimeObject->diff($date);

        return $diff->y;
    }

    public function getName()
    {
        return 'app_extension';
    }
}