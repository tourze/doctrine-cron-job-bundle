<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Provider;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;
use Tourze\DoctrineCronJobBundle\Provider\DoctrineProvider;
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\Symfony\CronJob\Request\CommandRequest;

class DoctrineProviderTest extends TestCase
{
    public function testGetCommands(): void
    {
        // 创建有效的任务
        $validJob = new CronJob();
        $validJob->setName('valid-job');
        $validJob->setCommand('php bin/console app:valid-command');
        $validJob->setSchedule('*/5 * * * *');
        $validJob->setValid(true);

        // 创建无效的任务
        $invalidJob = new CronJob();
        $invalidJob->setName('invalid-job');
        $invalidJob->setCommand('php bin/console app:invalid-command');
        $invalidJob->setSchedule('*/10 * * * *');
        $invalidJob->setValid(false);

        // 模拟Repository
        $repository = $this->createMock(CronJobRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$validJob]);

        // 创建Provider
        $provider = new DoctrineProvider($repository);

        // 获取命令并验证
        $commands = iterator_to_array($provider->getCommands());

        $this->assertCount(1, $commands);
        $this->assertInstanceOf(CommandRequest::class, $commands[0]);
        $this->assertEquals('php bin/console app:valid-command', $commands[0]->getCommand());
        $this->assertEquals('*/5 * * * *', $commands[0]->getCronExpression());
    }

    public function testEmptyCommands(): void
    {
        // 模拟Repository返回空数组
        $repository = $this->createMock(CronJobRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([]);

        // 创建Provider
        $provider = new DoctrineProvider($repository);

        // 获取命令并验证为空
        $commands = iterator_to_array($provider->getCommands());
        $this->assertEmpty($commands);
    }
}
