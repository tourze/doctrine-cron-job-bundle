<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;
use Tourze\DoctrineCronJobBundle\Provider\DoctrineProvider;
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\Symfony\CronJob\Request\CommandRequest;

/**
 * @internal
 */
#[CoversClass(DoctrineProvider::class)]
final class DoctrineProviderTest extends TestCase
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

        $repository = $this->createMock(CronJobRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([$validJob])
        ;

        // 创建Provider实例
        $provider = new DoctrineProvider($repository);

        // 获取命令并验证
        $commands = iterator_to_array($provider->getCommands());

        $this->assertCount(1, $commands);
        $firstCommand = $commands[0] ?? null;
        $this->assertInstanceOf(CommandRequest::class, $firstCommand);
        $this->assertEquals('php bin/console app:valid-command', $firstCommand->getCommand());
        $this->assertEquals('*/5 * * * *', $firstCommand->getCronExpression());
    }

    public function testEmptyCommands(): void
    {
        $repository = $this->createMock(CronJobRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([])
        ;

        // 创建Provider实例
        $provider = new DoctrineProvider($repository);

        // 获取命令并验证为空
        $commands = iterator_to_array($provider->getCommands());
        $this->assertEmpty($commands);
    }
}