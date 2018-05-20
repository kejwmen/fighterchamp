<?php

namespace AppBundle\Controller\Api;

use AppBundle\Repository\ClubRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ClubController extends Controller
{
    public function listAction(ClubRepository $clubRepository)
    {
        $clubs = $clubRepository->findAll();

        return $clubs;
    }
}
