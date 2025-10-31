<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(CronSql::class)]
final class CronSqlTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new CronSql();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'title' => ['title', '测试SQL任务'];
        yield 'sqlStatement' => ['sqlStatement', 'SELECT * FROM test_table'];
        yield 'cronExpression' => ['cronExpression', '0 */2 * * *'];
        yield 'valid' => ['valid', true];
        yield 'createdBy' => ['createdBy', 'admin'];
        yield 'updatedBy' => ['updatedBy', 'admin'];
        yield 'createTime' => ['createTime', new \DateTimeImmutable()];
        yield 'updateTime' => ['updateTime', new \DateTimeImmutable()];
    }

    public function testDefaultCronExpression(): void
    {
        $cronSql = new CronSql();
        $this->assertEquals('* * * * *', $cronSql->getCronExpression());
    }

    public function testId(): void
    {
        $cronSql = new CronSql();
        $this->assertEquals(null, $cronSql->getId());
    }
}
