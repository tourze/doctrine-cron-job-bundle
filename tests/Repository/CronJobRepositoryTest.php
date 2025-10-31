<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\ORMInvalidArgumentException;
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
    private CronJobRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(CronJobRepository::class);
    }

    public function testFindOneByWithOrderByShouldRespectOrdering(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronJob('z-later', 'echo "later"', '0 0 * * *');
        $entity1->setValid(true);
        $entity2 = $this->createCronJob('a-earlier', 'echo "earlier"', '0 1 * * *');
        $entity2->setValid(true);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $found = $this->repository->findOneBy(['valid' => true], ['name' => 'ASC']);

        $this->assertInstanceOf(CronJob::class, $found);
        $this->assertEquals('a-earlier', $found->getName());
    }

    public function testFindOneByNullDescriptionField(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('null-desc', 'echo "test"', '0 0 * * *');
        $entity->setDescription(null);
        $this->persistAndFlush($entity);

        $found = $this->repository->findOneBy(['description' => null]);

        $this->assertInstanceOf(CronJob::class, $found);
        $this->assertEquals('null-desc', $found->getName());
        $this->assertNull($found->getDescription());
    }

    public function testFindOneByNullValidField(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('null-valid', 'echo "test"', '0 0 * * *');
        $entity->setValid(null);
        $this->persistAndFlush($entity);

        $found = $this->repository->findOneBy(['valid' => null]);

        $this->assertInstanceOf(CronJob::class, $found);
        $this->assertEquals('null-valid', $found->getName());
        $this->assertNull($found->isValid());
    }

    public function testFindByNullDescriptionField(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronJob('with-desc', 'echo "test1"', '0 0 * * *');
        $entity1->setDescription('test description');
        $entity2 = $this->createCronJob('without-desc', 'echo "test2"', '0 1 * * *');
        $entity2->setDescription(null);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findBy(['description' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('without-desc', $result[0]->getName());
        $this->assertNull($result[0]->getDescription());
    }

    public function testFindByNullValidField(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronJob('valid-null', 'echo "test1"', '0 0 * * *');
        $entity1->setValid(null);
        $entity2 = $this->createCronJob('valid-true', 'echo "test2"', '0 1 * * *');
        $entity2->setValid(true);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findBy(['valid' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('valid-null', $result[0]->getName());
        $this->assertNull($result[0]->isValid());
    }

    public function testCountWithNullDescriptionField(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronJob('with-desc', 'echo "test1"', '0 0 * * *');
        $entity1->setDescription('test description');
        $entity2 = $this->createCronJob('without-desc', 'echo "test2"', '0 1 * * *');
        $entity2->setDescription(null);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $count = $this->repository->count(['description' => null]);

        $this->assertEquals(1, $count);
    }

    public function testCountWithNullValidField(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronJob('valid-null', 'echo "test1"', '0 0 * * *');
        $entity1->setValid(null);
        $entity2 = $this->createCronJob('valid-false', 'echo "test2"', '0 1 * * *');
        $entity2->setValid(false);
        $entity3 = $this->createCronJob('valid-true', 'echo "test3"', '0 2 * * *');
        $entity3->setValid(true);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);
        $this->persistAndFlush($entity3);

        $count = $this->repository->count(['valid' => null]);

        $this->assertEquals(1, $count);
    }

    public function testSaveShouldPersistEntity(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('test-save', 'echo "save test"', '0 0 * * *');

        $this->repository->save($entity);

        $this->assertGreaterThan(0, $entity->getId());
        $this->assertEntityPersisted($entity);
    }

    public function testSaveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('test-no-flush', 'echo "no flush"', '0 0 * * *');

        $this->repository->save($entity, false);
        self::getEntityManager()->flush();

        $this->assertGreaterThan(0, $entity->getId());
        $this->assertEntityPersisted($entity);
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('test-remove', 'echo "remove test"', '0 0 * * *');
        $this->persistAndFlush($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $this->assertGreaterThan(0, $id);
        $this->assertEntityNotExists(CronJob::class, $id);
    }

    public function testRemoveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('test-remove-no-flush', 'echo "remove no flush"', '0 0 * * *');
        $this->persistAndFlush($entity);
        $id = $entity->getId();

        $this->repository->remove($entity, false);
        self::getEntityManager()->flush();

        $this->assertGreaterThan(0, $id);
        $this->assertEntityNotExists(CronJob::class, $id);
    }

    public function testSaveWithCompleteEntityDataShouldPersistAllFields(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('complete-entity', 'echo "complete test"', '0 0 * * *');
        $entity->setDescription('Complete test description');
        $entity->setValid(true);

        $this->repository->save($entity);

        $this->assertEntityPersisted($entity);
        $found = $this->repository->find($entity->getId());
        $this->assertNotNull($found);
        $this->assertEquals('Complete test description', $found->getDescription());
        $this->assertTrue($found->isValid());
    }

    public function testSaveWithNullableFieldsShouldPersistCorrectly(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('nullable-fields', 'echo "nullable test"', '0 0 * * *');
        $entity->setDescription(null);
        $entity->setValid(null);

        $this->repository->save($entity);

        $this->assertEntityPersisted($entity);
        $found = $this->repository->find($entity->getId());
        $this->assertNotNull($found);
        $this->assertNull($found->getDescription());
        $this->assertNull($found->isValid());
    }

    public function testRemoveNonPersistedEntityShouldThrowException(): void
    {
        $this->clearTable();
        $entity = $this->createCronJob('non-persisted', 'echo "test"', '0 0 * * *');

        $this->expectException(ORMInvalidArgumentException::class);
        $this->repository->remove($entity);
    }

    private function createCronJob(string $name, string $command, string $schedule): CronJob
    {
        $entity = new CronJob();
        $entity->setName($name);
        $entity->setCommand($command);
        $entity->setSchedule($schedule);

        return $entity;
    }

    public function testFindOneByOrderingLogic(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronJob('test-1', 'echo "test1"', '0 0 * * *');
        $entity1->setDescription('First description');
        $this->persistAndFlush($entity1);

        $entity2 = $this->createCronJob('test-2', 'echo "test2"', '0 1 * * *');
        $entity2->setDescription('Second description');
        $this->persistAndFlush($entity2);

        $found = $this->repository->findOneBy([], ['name' => 'DESC']);

        $this->assertInstanceOf(CronJob::class, $found);
        $this->assertEquals('test-2', $found->getName());
    }

    private function clearTable(): void
    {
        self::getEntityManager()->createQuery('DELETE FROM Tourze\DoctrineCronJobBundle\Entity\CronJob')->execute();
    }

    /**
     * 创建一个新的 CronJob 实体，但不持久化到数据库
     */
    protected function createNewEntity(): object
    {
        $entity = new CronJob();
        $entity->setName('test-cron-job-' . uniqid());
        $entity->setCommand('echo "Hello World"');
        $entity->setSchedule('0 0 * * *');
        $entity->setDescription('Test cron job description');
        $entity->setValid(true);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<CronJob>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }
}
