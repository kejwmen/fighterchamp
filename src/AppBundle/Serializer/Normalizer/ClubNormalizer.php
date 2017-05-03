<?php
/**
 * Created by PhpStorm.
 * User: slk500
 * Date: 22.04.17
 * Time: 21:49
 */

namespace AppBundle\Serializer\Normalizer;


use AppBundle\Entity\Club;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\scalar;

class ClubNormalizer implements NormalizerInterface
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
            '_self' => $this->router->generate('club_show',['id' => $object->getId()]),
        ];
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Club;
    }

}