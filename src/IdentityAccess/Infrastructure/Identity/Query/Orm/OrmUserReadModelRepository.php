<?php

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Identity\Query\Orm;

use Common\Shared\Domain\Query\Exception\NotFoundException;
use Common\Shared\Infrastructure\Query\Repository\OrmRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use IdentityAccess\Domain\Identity\Repository\CheckUserByEmailInterface;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Identity\Query\User;

/**
 * Class OrmUserReadModelRepository
 *
 * @package IdentityAccess\Infrastructure\Identity\Query\Orm
 */
class OrmUserReadModelRepository extends OrmRepository implements CheckUserByEmailInterface
{
    protected function getClass(): string
    {
        return User::class;
    }

    public function add(User $user): void
    {
        $this->register($user);
    }

    /**
     * @param UserId $id
     *
     * @return User
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    public function oneById(UserId $id): User
    {
        $qb = $this->repository
            ->createQueryBuilder('user')
            ->where('user.id = :id')
            ->setParameter('id', $id->toString())
        ;

        return $this->oneOrException($qb);
    }

    public function existsEmail(Email $email): ?UserId
    {
        $builder = $this->getUserByEmailQueryBuilder($email);

        $query = $builder->select($builder->getRootAliases()[0] . '.id')
            ->getQuery()
        ;

        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException $exception) {}

        return null;
    }

    private function getUserByEmailQueryBuilder(Email $email): QueryBuilder
    {
        $alias = 'user';

        return $this->repository
            ->createQueryBuilder($alias)
            ->where($alias . '.email.value = :email')
            ->setParameter('email', $email->toString())
        ;
    }

}
