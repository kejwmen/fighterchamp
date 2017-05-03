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
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\scalar;

class UserNormalizer implements NormalizerInterface
{

    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function normalize($object, $format = null, array $context = array())
    {


        return [
            'name'   => $object->getName(),
            'surname'   => $object->getSurname(),
            'male' => $object->getMale() ? 'M' : 'K',
            '_self' => $this->router->generate('user',['id' => $object->getId()]),
            'age' => $object->getAge(),
            'club' => $object->getClub() ?  $object->getClub()->getName() : null,
            'club_link' => $object->getClub() ?  $this->router->generate('club_show',['id' => $object->getClub()->getId()]) : null,
        ];

    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof User;
    }

}