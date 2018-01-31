<?php

class Member
{
    private $name;
    private $organization;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setOrganization(Organization $organization)
    {
        $this->organization = $organization;
    }

    public function getOrganization()
    {
        return $this->organization;
    }
}

class Organization
{
    private $name;
    private $members;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setMember($members)
    {
        $this->members = $members;
    }

    public function getMember()
    {
        return $this->members;
    }
}

class MemberNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'name'   => $object->getName(),
            'organization' => $this->serializer->normalize($object->getOrganization(), $format, $context)
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Member;
    }
}
class OrganizationNormalizer implements NormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    public function normalize($object, $format = null, array $context = array())
    {
        return [
            'name'   => $object->getName(),
            'member' => $this->serializer->normalize($object->getMember(), $format, $context)
        ];
    }

    public function supportsNormalization($data, $format = null): bool
    {
        return $data instanceof Organization;
    }
}

public function testOneOne()
{
    $member = new Member();
    $member->setName('Kévin');

    $org = new Organization();
    $org->setName('Les-Tilleuls.coop');
    $org->setMember($member);

    $member->setOrganization($org);

    var_dump($this->serializer->serialize($member, 'json')); // Infinity loop maximum nested function 256 etc.
}

//services.yml
serializer.testing:
          class: Symfony\Component\Serializer\Serializer
          arguments: [["@normalizer.organization", "@normalizer.member"], ["@serializer.encoder.json"]]
          public: true



