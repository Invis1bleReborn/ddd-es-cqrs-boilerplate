<?php

/*
 * This file is part of invis1ble/ddd-es-cqrs-boilerplate.
 *
 * (c) Invis1ble <opensource.invis1ble@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace IdentityAccess\Infrastructure\Identity\Query\Orm;

use Common\Shared\Infrastructure\Query\Repository\OrmRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use IdentityAccess\Domain\Identity\Exception\NonUniqueUserException;
use IdentityAccess\Domain\Identity\Repository\CheckUserByEmailInterface;
use IdentityAccess\Domain\Identity\ValueObject\Email;
use IdentityAccess\Domain\Identity\ValueObject\UserId;
use IdentityAccess\Infrastructure\Identity\Query\User;

/**
 * Class OrmUserReadModelRepository.
 */
class OrmUserReadModelRepository extends OrmRepository implements CheckUserByEmailInterface
{
    /**
     * {@inheritdoc}
     */
    public function existsEmail(Email $email): ?UserId
    {
        $builder = $this->getUserByEmailQueryBuilder($email);

        $query = $builder->select($builder->getRootAliases()[0] . '.id')
            ->getQuery()
        ;

        try {
            return $query->getSingleScalarResult();
        } catch (NoResultException $exception) {
            return null;
        } catch (NonUniqueResultException $exception) {
            throw new NonUniqueUserException(sprintf(
                'Non-unique user with email %s exists.',
                $email->toString()
            ));
        }
    }

    protected function getClass(): string
    {
        return User::class;
    }

    private function getUserByEmailQueryBuilder(Email $email): QueryBuilder
    {
        $alias = 'user';

        return $this->repository
            ->createQueryBuilder($alias)
            ->where($alias . '.email = :email')
            ->setParameter('email', $email->toString())
        ;
    }
}
