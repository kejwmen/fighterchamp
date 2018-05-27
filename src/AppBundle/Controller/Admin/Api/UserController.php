<?php

namespace AppBundle\Controller\Admin\Api;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function indexAction(EntityManager $em)
    {
        $users = $em->getRepository(User::class)->findAll();

        return $users;
    }
}
