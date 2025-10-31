<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Provider;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;
use Tourze\DoctrineCronJobBundle\Provider\DoctrineProvider;
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\PHPUnitSymfonyKernelTest\AbstractIntegrationTestCase;
use Tourze\Symfony\CronJob\Request\CommandRequest;

/**
 * @internal
 */
#[CoversClass(DoctrineProvider::class)]
#[RunTestsInSeparateProcesses]
final class DoctrineProviderTest extends AbstractIntegrationTestCase
{
    protected function onSetUp(): void
    {
        // 测试使用Mock对象
    }

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

        // 将Mock注入到容器中
        self::getContainer()->set(CronJobRepository::class, $repository);

        // 从容器获取Provider实例
        $provider = self::getService(DoctrineProvider::class);

        // 获取命令并验证
        $commands = iterator_to_array($provider->getCommands());

        $this->assertCount(1, $commands);
        $this->assertInstanceOf(CommandRequest::class, $commands[0]);
        $this->assertEquals('php bin/console app:valid-command', $commands[0]->getCommand());
        $this->assertEquals('*/5 * * * *', $commands[0]->getCronExpression());
    }

    public function testEmptyCommands(): void
    {
        $repository = $this->createMock(CronJobRepository::class);
        $repository->expects($this->once())
            ->method('findBy')
            ->with(['valid' => true])
            ->willReturn([])
        ;

        // 将Mock注入到容器中
        self::getContainer()->set(CronJobRepository::class, $repository);

        // 从容器获取Provider实例
        $provider = self::getService(DoctrineProvider::class);

        // 获取命令并验证为空
        $commands = iterator_to_array($provider->getCommands());
        $this->assertEmpty($commands);
    }
}
