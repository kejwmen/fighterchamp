<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\Fight;
use AppBundle\Entity\Tournament;
use AppBundle\Entity\User;
use AppBundle\Form\EditUser;
use AppBundle\Form\FightType;
use AppBundle\Form\PairType;
use AppBundle\Form\RegistrationType;
use AppBundle\Form\SignUpTournamentType;
use AppBundle\Entity\SignUpTournament;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/admin")
 */
class UserAdminController extends Controller
{

    /**
     * @Route("/zawodnik", name="admin_user_list")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findAll();

        $tournament = $this->getDoctrine()
            ->getRepository(Tournament::class)
            ->findNewestOne();

        return $this->render('admin/user/list.html.twig',
            [
                'users' => $users,
                'tournament' => $tournament
            ]
        );
    }

}