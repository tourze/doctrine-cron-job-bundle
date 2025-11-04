<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @internal
 */
#[CoversClass(CronJobRepository::class)]
#[RunTestsInSeparateProcesses]
final class CronJobRepositoryTest extends AbstractRepositoryTestCase
{
    private ?CronJobRepository $repository = null;

    private ?EntityManagerInterface $entityManager = null;

    protected function onSetUp(): void
    {
        $metadata = new ClassMetadata(CronJob::class);
        $metadata->setIdentifier(['id']);

        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->entityManager
            ->method('getClassMetadata')
            ->with(CronJob::class)
            ->willReturn($metadata);

        $registry = $this->createMock(ManagerRegistry::class);
        $registry
            ->method('getManagerForClass')
            ->with(CronJob::class)
            ->willReturn($this->entityManager);
        $registry
            ->method('getManager')
            ->willReturn($this->entityManager);

        $this->repository = new CronJobRepository($registry);
    }

    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository();
    }

    public function testSaveShouldPersistEntity(): void
    {
        $entity = $this->createCronJob();

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
        $entity = $this->createCronJob();

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
        $entity = $this->createCronJob();

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
        $entity = $this->createCronJob();

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
        $entity = $this->createCronJob();

        $this->entityManager
            ->expects($this->once())
            ->method('remove')
            ->with($entity);
        $this->entityManager
            ->expects($this->once())
            ->method('flush');

        $this->repository()->remove($entity, true);
    }

    public function testFindWithExistingIdShouldReturnEntity(): void
    {
        $expected = $this->createCronJob();

        $this->entityManager
            ->expects($this->once())
            ->method('find')
            ->with(CronJob::class, 42, null, null)
            ->willReturn($expected);

        $this->assertSame($expected, $this->repository()->find(42));
    }

    private function repository(): CronJobRepository
    {
        if (null === $this->repository) {
            throw new \LogicException('CronJobRepository 未初始化');
        }

        return $this->repository;
    }

    private function createCronJob(): CronJob
    {
        $cronJob = new CronJob();
        $cronJob->setName('test-job-' . uniqid());
        $cronJob->setCommand('echo "test"');
        $cronJob->setSchedule('0 0 * * *');
        $cronJob->setValid(true);

        return $cronJob;
    }
}
