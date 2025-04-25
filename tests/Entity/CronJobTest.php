<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;

class CronJobTest extends TestCase
{
    public function testEntityGettersAndSetters(): void
    {
        $job = new CronJob();

        // 测试名称
        $job->setName('test-job');
        $this->assertEquals('test-job', $job->getName());

        // 测试命令
        $job->setCommand('php bin/console app:command');
        $this->assertEquals('php bin/console app:command', $job->getCommand());

        // 测试计划表达式
        $job->setSchedule('* * * * *');
        $this->assertEquals('* * * * *', $job->getSchedule());

        // 测试描述
        $job->setDescription('Test description');
        $this->assertEquals('Test description', $job->getDescription());

        // 测试有效状态
        $job->setValid(true);
        $this->assertTrue($job->isValid());

        // 测试创建人
        $job->setCreatedBy('admin');
        $this->assertEquals('admin', $job->getCreatedBy());

        // 测试更新人
        $job->setUpdatedBy('admin');
        $this->assertEquals('admin', $job->getUpdatedBy());

        // 测试创建时间
        $time = new \DateTime();
        $job->setCreateTime($time);
        $this->assertSame($time, $job->getCreateTime());

        // 测试更新时间
        $time = new \DateTime();
        $job->setUpdateTime($time);
        $this->assertSame($time, $job->getUpdateTime());
    }

    public function testToString(): void
    {
        $job = new CronJob();

        // 没有ID时应返回空字符串
        $this->assertEquals('', (string)$job);

        // 设置名称后应返回名称
        $job->setName('test-job');

        // 反射设置id
        $reflectionClass = new \ReflectionClass(CronJob::class);
        $property = $reflectionClass->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($job, 1);

        $this->assertEquals('test-job', (string)$job);
    }
}
