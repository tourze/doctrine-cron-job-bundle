<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;
use Tourze\DoctrineCronJobBundle\Provider\CronSqlProvider;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\Symfony\CronJob\Request\CommandRequest;

/**
 * @internal
 */
#[CoversClass(CronSqlProvider::class)]
#[RunTestsInSeparateProcesses]
final class CronSqlProviderTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 测试使用Mock对象
    }

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

        $repository = $this->createMock(CronSqlRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$validSqlJob])
        ;

        // 将Mock注入到容器中
        self::getContainer()->set(CronSqlRepository::class, $repository);

        // 从容器获取Provider实例
        $provider = self::getService(CronSqlProvider::class);

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
        $repository = $this->createMock(CronSqlRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([])
        ;

        // 将Mock注入到容器中
        self::getContainer()->set(CronSqlRepository::class, $repository);

        // 从容器获取Provider实例
        $provider = self::getService(CronSqlProvider::class);

        // 获取命令并验证为空
        $commands = iterator_to_array($provider->getCommands());
        $this->assertEmpty($commands);
    }
}
