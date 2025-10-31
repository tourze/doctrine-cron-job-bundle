<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Entity;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;
use Tourze\PHPUnitDoctrineEntity\AbstractEntityTestCase;

/**
 * @internal
 */
#[CoversClass(CronJob::class)]
final class CronJobTest extends AbstractEntityTestCase
{
    protected function createEntity(): object
    {
        return new CronJob();
    }

    /**
     * @return iterable<string, array{string, mixed}>
     */
    public static function propertiesProvider(): iterable
    {
        yield 'name' => ['name', 'test-job'];
        yield 'command' => ['command', 'php bin/console app:command'];
        yield 'schedule' => ['schedule', '* * * * *'];
        yield 'description' => ['description', 'Test description'];
        yield 'valid' => ['valid', true];
        yield 'createdBy' => ['createdBy', 'admin'];
        yield 'updatedBy' => ['updatedBy', 'admin'];
        yield 'createTime' => ['createTime', new \DateTimeImmutable()];
        yield 'updateTime' => ['updateTime', new \DateTimeImmutable()];
    }

    public function testToString(): void
    {
        $job = new CronJob();

        // 没有ID时应返回空字符串
        $this->assertEquals('', (string) $job);

        // 设置名称后应返回名称
        $job->setName('test-job');

        // 反射设置id
        $reflectionClass = new \ReflectionClass(CronJob::class);
        $property = $reflectionClass->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($job, 1);

        $this->assertEquals('test-job', (string) $job);
    }
}
