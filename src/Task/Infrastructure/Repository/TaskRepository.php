<?php

declare(strict_types=1);

namespace Task\Infrastructure\Repository;

use Task\Domain\Entity\Task;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @extends ServiceEntityRepository<Task>
 *
 * @method Task|null find($id, $lockMode = null, $lockVersion = null)
 * @method Task|null findOneBy(array $criteria, array $orderBy = null)
 * @method Task[]    findAll()
 * @method Task[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(Task $task, bool $flush = true): void
    {
        $this->getEntityManager()->persist($task);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Task $task, bool $flush = true): void
    {
        $this->getEntityManager()->remove($task);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function softDelete(Task $task, ?string $deletedBy = null): void
    {
        $task->setDeletedAt(new \DateTimeImmutable());

        if ($deletedBy !== null) {
            $task->setDeletedBy($deletedBy);
        }

        $this->save($task);
    }

    private function createActiveQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->where('t.deletedAt IS NULL');
    }

    public function findAllActive(array $orderBy = ['createdAt' => 'DESC']): array
    {
        $qb = $this->createActiveQueryBuilder();

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy('t.' . $field, $direction);
        }

        return $qb->getQuery()->getResult();
    }

    public function findByNameLike(string $name): array
    {
        return $this->createActiveQueryBuilder()
            ->andWhere('t.name LIKE :name')
            ->setParameter('name', '%' . $name . '%')
            ->orderBy('t.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findActivePaginated(int $page = 1, int $limit = 10, array $orderBy = ['createdAt' => 'DESC']): array
    {
        $qb = $this->createActiveQueryBuilder();

        foreach ($orderBy as $field => $direction) {
            $qb->addOrderBy('t.' . $field, $direction);
        }

        return $qb->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function countActive(): int
    {
        return $this->createActiveQueryBuilder()
            ->select('COUNT(t.id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findByCreatedAtRange(\DateTimeInterface $from, \DateTimeInterface $to): array
    {
        return $this->createActiveQueryBuilder()
            ->andWhere('t.createdAt >= :from')
            ->andWhere('t.createdAt <= :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->orderBy('t.createdAt', 'ASC')
            ->getQuery()
            ->getResult();
    }
}