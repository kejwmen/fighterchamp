<?php

namespace AppBundle\Serializer\Normalizer;

use AppBundle\Entity\UserFight;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;
use Symfony\Component\PropertyInfo\PropertyTypeExtractorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;
use Symfony\Component\Serializer\NameConverter\NameConverterInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class UserFightNormalizer extends  ObjectNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    private $router;

    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'isWinner' => $object->isWinner(),
            'isDraw' => $object->isDraw(),
            'isDisqualified' => $object->isDisqualified(),
            'isRedCorner' => $object->isRedCorner(),
            'user' => 'ddd'
            ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof UserFight;
    }
}