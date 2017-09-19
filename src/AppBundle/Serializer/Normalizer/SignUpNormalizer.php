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
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\scalar;

class SignUpNormalizer implements NormalizerInterface
{


    public function normalize($object, $format = null, array $context = array())
    {
        /**
         * @var $object SignUpTournament
         */

        return [
            'id' => $object->getId(),
            'formula'   => $object->getFormula(),
            'weight'   => $object->getFinallWeight(),
            'staz'=> $object->getStazTreningowy(),
            'age' => $object->howOldUserIs(),
            'male' => $object->getUser()->getMale() ? 'M' : 'K',
            'surname' => $object->getUser()->getSurname(),
            'name' => $object->getUser()->getName(),
            'club' => $object->getUser()->getClub() ? $object->getUser()->getClub()->getName() : null
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof SignUpTournament;
    }

}