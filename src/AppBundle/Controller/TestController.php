<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 19.09.16
 * Time: 11:57
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class TestController extends Controller
{

    /**
     * @Route("/test", name="test")
     */
    public function resultAction()
    {


        return $this->render('test.html.twig');


    }
}