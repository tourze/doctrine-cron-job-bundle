<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;
use Tourze\DoctrineCronJobBundle\Provider\CronSqlProvider;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;
use Tourze\Symfony\CronJob\Request\CommandRequest;

class CronSqlProviderTest extends TestCase
{
    public function testGetCommands(): void
    {
        // 创建有效的SQL任务
        $validSqlJob = new CronSql();
        $validSqlJob->setTitle('valid-sql-job');
        $validSqlJob->setSqlStatement('SELECT * FROM users');
        $validSqlJob->setCronExpression('*/30 * * * *');
        $validSqlJob->setValid(true);

        // 创建无效的SQL任务
        $invalidSqlJob = new CronSql();
        $invalidSqlJob->setTitle('invalid-sql-job');
        $invalidSqlJob->setSqlStatement('SELECT * FROM products');
        $invalidSqlJob->setCronExpression('0 0 * * *');
        $invalidSqlJob->setValid(false);

        // 模拟Repository
        $repository = $this->createMock(CronSqlRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$validSqlJob]);

        // 创建Provider
        $provider = new CronSqlProvider($repository);

        // 获取命令并验证
        $commands = iterator_to_array($provider->getCommands());

        $this->assertCount(1, $commands);
        $this->assertInstanceOf(CommandRequest::class, $commands[0]);
        $this->assertEquals('dbal:run-sql', $commands[0]->getCommand());
        $this->assertEquals('*/30 * * * *', $commands[0]->getCronExpression());

        // 验证选项
        $options = $commands[0]->getOptions();
        $this->assertArrayHasKey('sql', $options);
        $this->assertEquals('SELECT * FROM users', $options['sql']);
    }

    public function testEmptyCommands(): void
    {
        // 模拟Repository返回空数组
        $repository = $this->createMock(CronSqlRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([]);

        // 创建Provider
        $provider = new CronSqlProvider($repository);

        // 获取命令并验证为空
        $commands = iterator_to_array($provider->getCommands());
        $this->assertEmpty($commands);
    }
}
