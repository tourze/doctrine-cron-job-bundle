<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\Tests\Repository;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractRepositoryTestCase;

/**
 * @template-extends AbstractRepositoryTestCase<CronSql>
 * @internal
 */
#[CoversClass(CronSqlRepository::class)]
#[RunTestsInSeparateProcesses]
final class CronSqlRepositoryTest extends AbstractRepositoryTestCase
{
    private CronSqlRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(CronSqlRepository::class);

        // 为 count 测试创建测试数据
        $currentTest = $this->name();
        if ('testCountWithDataFixtureShouldReturnGreaterThanZero' === $currentTest) {
            $cronSql = $this->createCronSql();
            self::getEntityManager()->persist($cronSql);
            self::getEntityManager()->flush();
        }
    }

    public function testSaveWithFlushShouldPersistEntity(): void
    {
        $entity = $this->createCronSql();

        $this->repository->save($entity, true);
        $id = $entity->getId();
        $this->assertNotNull($id);

        $result = $this->repository->find($id);
        $this->assertInstanceOf(CronSql::class, $result);
    }

    public function testSaveWithoutFlushShouldNotPersistEntity(): void
    {
        $entity = $this->createCronSql();

        $this->repository->save($entity, false);
        $id = $entity->getId();
        self::getEntityManager()->clear();

        $result = $this->repository->find($id);
        $this->assertNull($result);
    }

    public function testRemoveWithFlushShouldDeleteEntity(): void
    {
        $entity = $this->createCronSql();
        $this->repository->save($entity, true);

        $id = $entity->getId();
        $this->repository->remove($entity, true);

        $result = $this->repository->find($id);
        $this->assertNull($result);
    }

    public function testRemoveWithoutFlushShouldNotDeleteEntity(): void
    {
        $entity = $this->createCronSql();
        $this->repository->save($entity, true);

        $id = $entity->getId();
        $this->repository->remove($entity, false);

        $result = $this->repository->find($id);
        $this->assertInstanceOf(CronSql::class, $result);
    }

    protected function createNewEntity(): object
    {
        return $this->createCronSql();
    }

    /**
     * @return CronSqlRepository
     */
    protected function getRepository(): CronSqlRepository
    {
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
