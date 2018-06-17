<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Club;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

final class ClubRepository
{
    /**
     * @var EntityRepository
     */
    private $repository;

    public function __construct(EntityManager $entityManager)
    {
        $this->repository = $entityManager->getRepository(Club::class);
    }

    public function find(int $id): Club
    {
        return $this->repository->find($id);
    }

    public function findAll(): array
    {
        return $this->repository->createQueryBuilder('club')
            ->leftJoin('club.users', 'users')
            ->leftJoin('users.userFights', 'userFights')
            ->leftJoin('userFights.fight', 'fights')
            ->leftJoin('userFights.awards', 'awards')
            ->leftJoin('fights.tournament', 'tournament')
            ->addSelect('fights')
            ->addSelect('userFights')
            ->addSelect('awards')
            ->addSelect('users')
            ->getQuery()->execute();
    }
}