<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UserNormalizer implements NormalizerInterface
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof User;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        /**
         * @var $object User
         */

        return [
            'href' => $this->router->generate('user_show',['id' => $object->getId()]),
            'name' => $object->getName(),
            'surname' => $object->getSurname(),
            'male' => $object->getMale(),
            'record' => $this->countRecord($object),
            'club' => [
                'href' => $object->getClub() ?  $this->router->generate('club_show',['id' => $object->getClub()->getId()]) : null,
                'name' => $object->getClub() ? $object->getClub()->getName() : null,
            ],
            'type' => $object->getType(),
            'fights' => $this->formatFights($object)
        ];
    }

    private function countRecord(User $user)
    {
        $userRecord = new UserRecord();

        foreach ($user->getUserFights() as $userFight)
        {
            if ($this->isDraw($userFight)){
                $userRecord->addDraw();

            }elseif ($this->isWinner($userFight)) {
                $userRecord->addWin();
            }
            elseif ($this->isLose($userFight)){
                $userRecord->addLose();
            }
        }
            return $userRecord;
    }



    private function formatFights(User $user)
    {
        $fights = [];

       foreach ($user->getUserFights() as $userFight)
       {
           /**
            * @var $fight Fight
            */
           $fight = $userFight->getFight();
           $opponentUser = $this->getOpponent($userFight);

           $arrayFight = [

               'href' => $this->router->generate('fight_show', ['id' => $fight->getId()]),
               'formula' => $fight->getFormula(),
               'weight' => $fight->getWeight(),
               'user' => [
                   'href' => $this->router->generate('user_show',['id' => $opponentUser->getId()]),
                   'name' => $opponentUser->getName(),
                   'surname' => $opponentUser->getSurname(),
                   'male' => $opponentUser->getMale(),
                   'record' => $this->countRecord($opponentUser),
                   'club' => [
                       'href' => $opponentUser->getClub() ?  $this->router->generate('club_show',['id' => $opponentUser->getClub()->getId()]) : null,
                       'name' => $opponentUser->getClub() ? $opponentUser->getClub()->getName() : null,
                   ],
                   'type' => $opponentUser->getType(),
               ]
           ];

           $fights[]=$arrayFight;
       }

       return $fights;
    }

    private function isDraw(UserFight $userFight): bool
    {
        return $userFight->getFight()->getIsDraw();
    }

    private function isLose(UserFight $userFight): bool
    {
        return !$userFight->isWinner() || $userFight->isDisqualified();
    }

    private function isWinner(UserFight $userFight): bool
    {
        return $userFight->isWinner();
    }

    private function getOpponent(UserFight $userFight): User
    {
        $fight = $userFight->getFight();
        $usersFightCollection = $fight->getUsersFight();

        $usersFightCollection->removeElement($userFight);

        $opponentUserFight = $usersFightCollection->first();

        return $opponentUserFight->getUser();
    }


}