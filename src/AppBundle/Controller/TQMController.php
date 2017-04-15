<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 15.04.17
 * Time: 22:47
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class TQMController extends Controller
{
    /**
     * @Route("/nowosci", name="tqm_nowosci")
     */
    public function showAction()
    {
        return $this->render('tqm/news.html.twig', array(

        ));
    }
}