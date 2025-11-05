<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @template-extends AbstractRepositoryTestCase<CronJob>
 * @internal
 */
#[CoversClass(CronJobRepository::class)]
#[RunTestsInSeparateProcesses]
final class CronJobRepositoryTest extends AbstractRepositoryTestCase
{
    private CronJobRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(CronJobRepository::class);

        // 为 count 测试创建测试数据
        $currentTest = $this->name();
        if ('testCountWithDataFixtureShouldReturnGreaterThanZero' === $currentTest) {
            $cronJob = $this->createCronJob();
            self::getEntityManager()->persist($cronJob);
            self::getEntityManager()->flush();
        }
    }

    public function testSaveWithFlushShouldPersistEntity(): void
    {
        $entity = $this->createCronJob();

        $this->repository->save($entity, true);
        $id = $entity->getId();
        $this->assertNotNull($id);

        $result = $this->repository->find($id);
        $this->assertInstanceOf(CronJob::class, $result);
    }

    public function testSaveWithoutFlushShouldNotPersistEntity(): void
    {
        $entity = $this->createCronJob();

        $this->repository->save($entity, false);
        $id = $entity->getId();
        self::getEntityManager()->clear();

        $result = $this->repository->find($id);
        $this->assertNull($result);
    }

    public function testRemoveWithFlushShouldDeleteEntity(): void
    {
        $entity = $this->createCronJob();
        $this->repository->save($entity, true);

        $id = $entity->getId();
        $this->repository->remove($entity, true);

        $result = $this->repository->find($id);
        $this->assertNull($result);
    }

    public function testRemoveWithoutFlushShouldNotDeleteEntity(): void
    {
        $entity = $this->createCronJob();
        $this->repository->save($entity, true);

        $id = $entity->getId();
        $this->repository->remove($entity, false);

        $result = $this->repository->find($id);
        $this->assertInstanceOf(CronJob::class, $result);
    }

    protected function createNewEntity(): object
    {
        return $this->createCronJob();
    }

    /**
     * @return CronJobRepository
     */
    protected function getRepository(): CronJobRepository
    {
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
