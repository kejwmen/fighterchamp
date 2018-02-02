<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 22.04.17
 * Time: 21:49
 */

namespace AppBundle\Serializer\Normalizer;


use AppBundle\Entity\Club;
use AppBundle\Entity\SignUpTournament;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\scalar;

class SignUpNormalizer implements NormalizerInterface
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'id' => $object->getId(),
            'formula'   => $object->getFormula(),
            'weight'   => $object->getFinallWeight(),
            'staz' => $object->getStazTreningowy(),
            'youtubeId' => $object->getYouTubeId(),
            'musicArtistAndTitle' => $object->getMusicArtistAndTitle(),
            'isPaid' => $object->isPaid(),
            'user'=> [
                'href' => $this->router->generate('user_show', ['id' => $object->getUser()->getId()]),
                'name' => $object->getUser()->getName(),
                'surname' => $object->getUser()->getSurname(),
                'male' => $object->getUser()->getMale(),
                'birthDay' => $object->getUser()->getBirthDay(),
                'record' => $this->countRecord($object->getUser()),
                'club' => [
                    'href' => $object->getUser()->getClub() ? $this->router->generate('club_show', ['id' => $object->getUser()->getClub()->getId()]) : null,
                    'name' => $object->getUser()->getClub() ? $object->getUser()->getClub()->getName() : null,
                    ]
            ]
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof SignUpTournament;
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