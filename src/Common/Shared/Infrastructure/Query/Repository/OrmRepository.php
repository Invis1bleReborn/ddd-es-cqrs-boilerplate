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

use Broadway\ReadModel\Identifiable;
use Broadway\ReadModel\Repository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

/**
 * Class OrmRepository.
 */
abstract class OrmRepository implements Repository
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

    public function save(Identifiable $data): void
    {
        $this->entityManager->persist($data);
        $this->entityManager->flush();
    }

    public function find($id): ?Identifiable
    {
        $model = $this->repository->find((string)$id);
        /* @var $model Identifiable */

        return $model;
    }

    public function findBy(array $fields): array
    {
        return $this->repository->findBy($fields);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }

    public function remove($id): void
    {
        $model = $this->find($id);

        if (null === $model) {
            return;
        }

        $this->entityManager->remove($model);
        $this->entityManager->flush();
    }

    abstract protected function getClass(): string;
}
