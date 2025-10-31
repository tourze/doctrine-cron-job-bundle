<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    private CronSqlRepository $repository;

    protected function onSetUp(): void
    {
        $this->repository = self::getService(CronSqlRepository::class);
    }

    public function testFindWithEmptyIdShouldReturnNull(): void
    {
        $found = $this->repository->find('');

        $this->assertNull($found);
    }

    public function testFindOneByWithOrderByShouldRespectOrdering(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronSql('Z Later SQL', 'SELECT 1', '0 0 * * *');
        $entity1->setValid(true);
        $entity2 = $this->createCronSql('A Earlier SQL', 'SELECT 2', '0 1 * * *');
        $entity2->setValid(true);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $found = $this->repository->findOneBy(['valid' => true], ['title' => 'ASC']);

        $this->assertInstanceOf(CronSql::class, $found);
        $this->assertEquals('A Earlier SQL', $found->getTitle());
    }

    public function testFindOneByNullValidField(): void
    {
        $this->clearTable();
        $entity = $this->createCronSql('Null Valid SQL', 'SELECT 1', '0 0 * * *');
        $entity->setValid(null);
        $this->persistAndFlush($entity);

        $found = $this->repository->findOneBy(['valid' => null]);

        $this->assertInstanceOf(CronSql::class, $found);
        $this->assertEquals('Null Valid SQL', $found->getTitle());
        $this->assertNull($found->isValid());
    }

    public function testFindByNullValidField(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronSql('Valid Null', 'SELECT 1', '0 0 * * *');
        $entity1->setValid(null);
        $entity2 = $this->createCronSql('Valid True', 'SELECT 2', '0 1 * * *');
        $entity2->setValid(true);
        $this->persistAndFlush($entity1);
        $this->persistAndFlush($entity2);

        $result = $this->repository->findBy(['valid' => null]);

        $this->assertIsArray($result);
        $this->assertCount(1, $result);
        $this->assertEquals('Valid Null', $result[0]->getTitle());
        $this->assertNull($result[0]->isValid());
    }

    public function testCountWithNullValidField(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronSql('Valid Null', 'SELECT 1', '0 0 * * *');
        $entity1->setValid(null);
        $entity2 = $this->createCronSql('Valid False', 'SELECT 2', '0 1 * * *');
        $entity2->setValid(false);
        $entity3 = $this->createCronSql('Valid True', 'SELECT 3', '0 2 * * *');
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
        $entity = $this->createCronSql('Save Test', 'SELECT NOW()', '0 0 * * *');

        $this->repository->save($entity);

        $this->assertNotEmpty($entity->getId());
        $this->assertEntityPersisted($entity);
    }

    public function testSaveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $this->clearTable();
        $entity = $this->createCronSql('No Flush Test', 'SELECT NOW()', '0 0 * * *');

        $this->repository->save($entity, false);
        self::getEntityManager()->flush();

        $this->assertNotEmpty($entity->getId());
        $this->assertEntityPersisted($entity);
    }

    public function testRemoveShouldDeleteEntity(): void
    {
        $this->clearTable();
        $entity = $this->createCronSql('Remove Test', 'SELECT NOW()', '0 0 * * *');
        $this->persistAndFlush($entity);
        $id = $entity->getId();

        $this->repository->remove($entity);

        $this->assertNotEmpty($id);
        $this->assertEntityNotExists(CronSql::class, $id);
    }

    public function testRemoveWithFlushFalseShouldNotFlushImmediately(): void
    {
        $this->clearTable();
        $entity = $this->createCronSql('Remove No Flush Test', 'SELECT NOW()', '0 0 * * *');
        $this->persistAndFlush($entity);
        $id = $entity->getId();

        $this->repository->remove($entity, false);
        self::getEntityManager()->flush();

        $this->assertNotEmpty($id);
        $this->assertEntityNotExists(CronSql::class, $id);
    }

    public function testSaveWithCompleteEntityDataShouldPersistAllFields(): void
    {
        $this->clearTable();
        $entity = $this->createCronSql('Complete Entity', 'SELECT * FROM users', '0 0 * * *');
        $entity->setValid(true);

        $this->repository->save($entity);

        $this->assertEntityPersisted($entity);
        $found = $this->repository->find($entity->getId());
        $this->assertNotNull($found);
        $this->assertEquals('Complete Entity', $found->getTitle());
        $this->assertEquals('SELECT * FROM users', $found->getSqlStatement());
        $this->assertTrue($found->isValid());
    }

    public function testSaveWithNullableValidFieldShouldPersistCorrectly(): void
    {
        $this->clearTable();
        $entity = $this->createCronSql('Test Title', 'SELECT NOW()', '0 0 * * *');
        $entity->setValid(null);

        $this->repository->save($entity);

        $this->assertEntityPersisted($entity);
        $found = $this->repository->find($entity->getId());
        $this->assertNotNull($found);
        $this->assertEquals('Test Title', $found->getTitle());
        $this->assertEquals('SELECT NOW()', $found->getSqlStatement());
        $this->assertNull($found->isValid());
    }

    public function testRemoveNonPersistedEntityShouldNotThrowException(): void
    {
        $this->clearTable();
        $entity = $this->createCronSql('Non Persisted', 'SELECT 1', '0 0 * * *');

        $this->repository->remove($entity);

        $this->assertNull($entity->getId());
    }

    private function createCronSql(string $title, string $sqlStatement, string $cronExpression): CronSql
    {
        $entity = new CronSql();
        $entity->setTitle($title);
        $entity->setSqlStatement($sqlStatement);
        $entity->setCronExpression($cronExpression);

        return $entity;
    }

    public function testFindOneByOrderingLogic(): void
    {
        $this->clearTable();
        $entity1 = $this->createCronSql('test-1', 'SELECT 1', '0 0 * * *');
        $this->persistAndFlush($entity1);

        $entity2 = $this->createCronSql('test-2', 'SELECT 2', '0 1 * * *');
        $this->persistAndFlush($entity2);

        $found = $this->repository->findOneBy([], ['title' => 'DESC']);

        $this->assertInstanceOf(CronSql::class, $found);
        $this->assertEquals('test-2', $found->getTitle());
    }

    private function clearTable(): void
    {
        self::getEntityManager()->createQuery('DELETE FROM Tourze\DoctrineCronJobBundle\Entity\CronSql')->execute();
    }

    /**
     * 创建一个新的 CronSql 实体，但不持久化到数据库
     */
    protected function createNewEntity(): object
    {
        $entity = new CronSql();
        $entity->setTitle('Test SQL Query');
        $entity->setSqlStatement('SELECT NOW()');
        $entity->setCronExpression('0 0 * * *');
        $entity->setValid(true);

        return $entity;
    }

    /**
     * @return ServiceEntityRepository<CronSql>
     */
    protected function getRepository(): ServiceEntityRepository
    {
        return $this->repository;
    }
}
