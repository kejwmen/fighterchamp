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

    public function createFightFromSignUps(SignUpTournament $signUp1, SignUpTournament $signUp2): void
    {
        $formula = $this->getHighestFormula($signUp1, $signUp2);
        $weight = $this->getHighestWeight($signUp1, $signUp2);

        $fight = new Fight($formula, $weight);

        $userFight1 = new UserFight($signUp1->getUser(), $fight);
        $userFight1->setIsRedCorner(true);
        $userFight2 = new UserFight($signUp2->getUser(), $fight);

        $tournament = $signUp1->getTournament(); //todo should take both signUps

        $fight->setTournament($tournament);

        $numberOfFights = $tournament->getSignUpTournament()->count(); //todo should count fights not signups

        $fight->setPosition($numberOfFights + 1);
        $fight->setDay($tournament->getStart());

        $this->entityManager->persist($fight);
        $this->entityManager->persist($userFight1);
        $this->entityManager->persist($userFight2);
        $this->entityManager->flush();
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