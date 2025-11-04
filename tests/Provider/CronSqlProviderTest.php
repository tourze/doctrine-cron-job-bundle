<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;
use Tourze\DoctrineCronJobBundle\Provider\CronSqlProvider;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;
use Tourze\Symfony\CronJob\Request\CommandRequest;

/**
 * @internal
 */
#[CoversClass(CronSqlProvider::class)]
final class CronSqlProviderTest extends TestCase
{
    public function testGetCommands(): void
    {
        // 创建有效的SQL任务
        $validSqlJob = new CronSql();
        $validSqlJob->setTitle('valid-sql-job');
        $validSqlJob->setSqlStatement('SELECT * FROM users');
        $validSqlJob->setCronExpression('*/30 * * * *');
        $validSqlJob->setValid(true);

        // 创建模拟仓库
        $repository = $this->createMock(CronSqlRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$validSqlJob])
        ;

        // 创建Provider实例
        $provider = new CronSqlProvider($repository);

        // 获取命令并验证
        $commands = iterator_to_array($provider->getCommands());

        $this->assertCount(1, $commands);
        $firstCommand = $commands[0] ?? null;
        $this->assertInstanceOf(CommandRequest::class, $firstCommand);
        $this->assertEquals('dbal:run-sql', $firstCommand->getCommand());
        $this->assertEquals('*/30 * * * *', $firstCommand->getCronExpression());

        // 验证选项
        $options = $firstCommand->getOptions();
        $this->assertArrayHasKey('sql', $options);
        $this->assertEquals('SELECT * FROM users', $options['sql']);
    }

    public function testEmptyCommands(): void
    {
        $repository = $this->createMock(CronSqlRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([])
        ;

        // 创建Provider实例
        $provider = new CronSqlProvider($repository);

        // 获取命令并验证为空
        $commands = iterator_to_array($provider->getCommands());
        $this->assertEmpty($commands);
    }
}
