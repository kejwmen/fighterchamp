<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Fight;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class FightNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'href' => $this->router->generate('fight_show', ['id' => $object->getId()]),
            'formula' => $object->getFormula(),
            'weight' => $object->getWeight(),
            'youtubeId' => $object->getYoutubeId(),
            'tournament' => [
                'href' => $this->router->generate('tournament_show', ['id' => $object->getTournament()->getId()]),
                'name' => $object->getTournament()->getName(),
                'start' => $object->getTournament()->getStart()
            ],
            'usersFight' => array_map(
                function (UserFight $userFight) {
                    return [
                        'isWinner' => $userFight->isWinner(),
                        'isDraw' => $userFight->isDraw(),
                        'isDisqualified' => $userFight->isDisqualified(),
                        'isRedCorner' => $userFight->isRedCorner(),
                        'result' => $userFight->getResult(),
                        'user' => [
                            'href' => $this->router->generate('user_show', ['id' => $userFight->getUser()->getId()]),
                            'name' => $userFight->getUser()->getName(),
                            'surname' => $userFight->getUser()->getSurname(),
                            'male' => $userFight->getUser()->getMale(),
                            'birthDay' => $userFight->getUser()->getBirthDay(),
                            'record' => $this->countRecord($userFight->getUser()),
                            'club' => $this->club($userFight->getUser()),
                            'type' => $userFight->getUser()->getType(),
                        ]
                    ];
                }, $object->getUsersFight()->toArray())
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Fight;
    }

    private function club(User$user)
    {
        if(!$user->getClub()){
            return null;
        }
        return [
            'href' => $this->router->generate('club_show', ['id' => $user->getClub()->getId()]),
            'name' => $user->getClub()->getName(),
        ];
    }

    private function countRecord(User $user)
    {
        $userRecord = new UserRecord();

        foreach ($user->getUserFights() as $userFight) {
            if ($this->isDraw($userFight)) {
                $userRecord->addDraw();

            } elseif ($this->isWinner($userFight)) {
                $userRecord->addWin();
            } elseif ($this->isLose($userFight)) {
                $userRecord->addLose();
            }
        }
        return [
            'win' => $userRecord->win,
            'draw' => $userRecord->draw,
            'lose' => $userRecord->lose
        ];
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
}