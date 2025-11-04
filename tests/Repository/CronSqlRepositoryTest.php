<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(CronSqlRepository::class)]
#[RunTestsInSeparateProcesses]
final class CronSqlRepositoryTest extends AbstractRepositoryTestCase
{
    private ?CronSqlRepository $repository = null;

    private ?EntityManagerInterface $entityManager = null;

    protected function onSetUp(): void
    {
        $metadata = new ClassMetadata(CronSql::class);
        $metadata->setIdentifier(['id']);

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager
            ->method('getClassMetadata')
            ->with(CronSql::class)
            ->willReturn($metadata);

        $registry = $this->createMock(ManagerRegistry::class);
        $registry
            ->method('getManagerForClass')
            ->with(CronSql::class)
            ->willReturn($this->entityManager);
        $registry
            ->method('getManager')
            ->willReturn($this->entityManager);

        $this->repository = new CronSqlRepository($registry);
    }

    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository();
    }

    public function testFindWithExistingIdShouldReturnEntity(): void
    {
        $expected = $this->createCronSql();

        $this->entityManager
            ->expects($this->once())
            ->method('find')
            ->with(CronSql::class, '123', null, null)
            ->willReturn($expected);

        $this->assertSame($expected, $this->repository()->find('123'));
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createCronSql();

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($entity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository()->save($entity, true);
    }

    public function testSave(): void
    {
        $entity = $this->createCronSql();

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($entity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository()->save($entity, true);
    }

    public function testSaveWithoutImmediateFlush(): void
    {
        $entity = $this->createCronSql();

        $this->entityManager
            ->expects($this->once())
            ->method('persist')
            ->with($entity);
        $this->entityManager
            ->expects($this->never())
            ->method('flush');

        $this->repository()->save($entity, false);
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $entity = $this->createCronSql();

        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($entity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository()->remove($entity, true);
    }

    public function testRemove(): void
    {
        $entity = $this->createCronSql();

        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($entity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository()->remove($entity, true);
    }

    private function repository(): CronSqlRepository
    {
        if (null === $this->repository) {
            throw new \LogicException('CronSqlRepository 未初始化');
        }

        return $this->repository;
    }

    private function createCronSql(): CronSql
    {
        $cronSql = new CronSql();
        $cronSql->setTitle('cron-sql-' . uniqid());
        $cronSql->setSqlStatement('SELECT 1');
        $cronSql->setCronExpression('*/5 * * * *');
        $cronSql->setValid(true);

        return $cronSql;
    }
}
