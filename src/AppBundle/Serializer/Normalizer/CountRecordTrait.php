<?php
/**
 * Created by PhpStorm.
 * User: slk
 * Date: 2/4/18
 * Time: 6:02 PM
 */

namespace AppBundle\Serializer\Normalizer;


use AppBundle\Entity\User;

trait CountRecordTrait
{
    private function countRecord(User $user)
    {
        $userRecord = new UserRecord();

        foreach ($user->getUserFights() as $userFight) {

            switch ($userFight->getResult()){
                case 'win':
                    $userRecord->addWin();
                    break;
                case 'draw':
                    $userRecord->addDraw();
                    break;
                case 'lose':
                case 'disqualify':
                    $userRecord->addLose();
                    break;
            }

        }
        return [
            'win' => $userRecord->win,
            'draw' => $userRecord->draw,
            'lose' => $userRecord->lose
        ];
    }
}