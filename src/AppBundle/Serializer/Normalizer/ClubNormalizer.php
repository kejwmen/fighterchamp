<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 22.04.17
 * Time: 21:49
 */

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\Club;
use AppBundle\Entity\User;
use AppBundle\Entity\UserFight;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ClubNormalizer implements NormalizerInterface, SerializerAwareInterface
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
            'href' => $this->router->generate('club_show',['id' => $object->getId()]),
            'name'   => $object->getName(),
            'record' => $this->countRecordClub($object->getUsers()),
            'users' => $this->serializer->normalize($object->getUsers(), $format, $context)
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Club;
    }

    private function countRecordClub($users)
    {
        $win = $draw = $lose = 0;

        foreach ($users as $user){

           $record =  $this->countRecord($user);

            $win += $record['win'];
            $draw += $record['draw'];
            $lose += $record['lose'];
        }

        return [
            'win' => $win,
            'draw' => $draw,
            'lose' => $lose
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