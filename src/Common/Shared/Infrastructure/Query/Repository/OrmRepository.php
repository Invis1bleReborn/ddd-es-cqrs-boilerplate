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

namespace Common\Shared\Infrastructure\Query\Repository;

use Common\Shared\Domain\Query\Exception\NotFoundException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * Class OrmRepository.
 */
abstract class OrmRepository
{
    protected EntityRepository $repository;

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        $objectRepository = $entityManager->getRepository($this->getClass());
        /* @var EntityRepository $objectRepository */
        $this->repository = $objectRepository;
    }

    abstract protected function getClass(): string;

    /**
     * @param object $model
     */
    public function register($model): void
    {
        $this->entityManager->persist($model);
        $this->apply();
    }

    public function apply(): void
    {
        $this->entityManager->flush();
    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     * @throws NotFoundException
     */
    protected function oneOrException(QueryBuilder $queryBuilder)
    {
        $model = $queryBuilder
            ->getQuery()
            ->getOneOrNullResult()
        ;

        if (null === $model) {
            throw new NotFoundException();
        }

        return $model;
    }
}
