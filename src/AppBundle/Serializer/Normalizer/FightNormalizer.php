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
            'tournament' => [
                'href' => $this->router->generate('tournament_show', ['id' => $object->getTournament()->getId()]),
                'name' => $object->getTournament()->getName()
            ],
            'usersFight' => array_map(
                function ($object) use ($format, $context) {
                    return $this->serializer->normalize($object, $format, $context);
                },
                $object->getUsersFight()->toArray()
            ),
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Fight;
    }
}