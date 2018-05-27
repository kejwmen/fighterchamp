<?php

namespace AppBundle\Controller\Api\Admin;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    /**
     * @Route("/api/admin/users")
     */
    public function indexAction(EntityManager $em)
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $users = $em->getRepository(User::class)->findAll();

        return $users;
    }
}
