<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 18.01.17
 * Time: 00:17
 */

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class JustRenderViewController extends Controller
{
    /**
     * @Route("/kontakt", name="contact")
     */
    public function contactController()
    {
        return $this->render('contact/contact.html.twig');
    }

    /**
     * @Route("/regulamin", name="rules")
     */
    public function rulesController()
    {
        return $this->render('rules/rules.html.twig');
    }

}

