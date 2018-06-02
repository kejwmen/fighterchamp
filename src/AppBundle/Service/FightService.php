<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 4/16/18
 * Time: 10:13 AM
 */

namespace AppBundle\Service;

use AppBundle\Entity\Fight;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\UserFight;
use Doctrine\ORM\EntityManagerInterface;

class FightService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function toggleCorners(Fight $fight): void
    {
        $usersFight = $fight->getUsersFight();

        $userOneFight = $usersFight[0];
        $userTwoFight = $usersFight[1];

        $one = $userOneFight->isRedCorner();
        $two = $userTwoFight->isRedCorner();

        $this->convertNullToFalse($one);
        $this->convertNullToFalse($two);

        if($one === $two){
            $one = true;
            $two = false;
        }

        $one = ($one === true) ? false : true;
        $two = ($two === false) ? true : false;

        $userOneFight->setIsRedCorner($one);
        $userTwoFight->setIsRedCorner($two);
    }

    private function convertNullToFalse(&$arg)
    {
        $arg = $arg ?? false;
    }

    public function createFight(array $data) : Fight
    {
        $signUpRepo = $this->entityManager->getRepository(SignUpTournament::class);

        $signUp0 = $signUpRepo->find($data['ids'][0]);
        $signUp1 = $signUpRepo->find($data['ids'][1]);

        $formula = $this->getHighestFormula($signUp0, $signUp1);
        $weight = $this->getHighestWeight($signUp0, $signUp1);

        $fight = new Fight($formula, $weight);

        $userFightOne = new UserFight($signUp0->getUser(), $fight);
        $userFightTwo = new UserFight($signUp1->getUser(), $fight);

        $tournament = $signUp0->getTournament();

        $fight->setTournament($tournament);

        $numberOfFights = $tournament->getSignUpTournament()->count();

        $fight->setPosition($numberOfFights + 1);

        $fight->setDay($tournament->getStart());

        $this->entityManager->persist($fight);
        $this->entityManager->persist($userFightOne);
        $this->entityManager->persist($userFightTwo);
        $this->entityManager->flush();

        return $fight;
    }

    public function getHighestFormula(SignUpTournament $signUp0, SignUpTournament $signUp1): string
    {
        return ($signUp0->getFormula() <= $signUp1->getFormula()) ? $signUp0->getFormula() : $signUp1->getFormula();
    }

    public function getHighestWeight(SignUpTournament $signUp0, SignUpTournament $signUp1): string
    {
        return ($signUp0->getWeight() >= $signUp1->getWeight()) ? $signUp0->getWeight() : $signUp1->getWeight();
    }
}