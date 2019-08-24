<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * @Route("/walki")
 */
class FightController extends Controller
{
    /**
     * @Route("/{id}", name="fight_show")
     */
    public function showAction(Fight $fight)
    {
        $fight = $this->get('serializer.my')->normalize($fight);


        return $this->render('fight/show.html.twig',
            [
                'fight' => $fight,
            ]);
    }

    /**
     * @Route("", name="fight_list")
     */
    public function listAction()
    {
        return $this->render('fight/list.html.twig');
    }
}