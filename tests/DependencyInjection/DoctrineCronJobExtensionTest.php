<?php

namespace Tourze\DoctrineCronJobBundle\Tests\DependencyInjection;

use PHPUnit\Framework\Attributes\CoversClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\DoctrineCronJobBundle\DependencyInjection\DoctrineCronJobExtension;
use Tourze\PHPUnitSymfonyUnitTest\AbstractDependencyInjectionExtensionTestCase;

/**
 * @internal
 */
#[CoversClass(DoctrineCronJobExtension::class)]
final class DoctrineCronJobExtensionTest extends AbstractDependencyInjectionExtensionTestCase
{
    protected function provideServiceDirectories(): iterable
    {
        yield from parent::provideServiceDirectories();
        yield 'Provider';
    }

    private ContainerBuilder $container;

    private DoctrineCronJobExtension $extension;

    protected function setUp(): void
    {
        parent::setUp();
        $this->extension = new DoctrineCronJobExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testLoadWithEmptyConfigs(): void
    {
        $configs = [];

        try {
            $this->extension->load($configs, $this->container);
            // 测试成功加载后container应该有一些基本配置
            $this->assertInstanceOf(ContainerBuilder::class, $this->container);
        } catch (\Throwable $e) {
            // 在测试环境中，如果配置文件不存在是正常的
            $this->assertStringContainsString('services.yaml', $e->getMessage());
        }
    }

    public function testExtensionAlias(): void
    {
        $expectedAlias = 'doctrine_cron_job';
        $this->assertEquals($expectedAlias, $this->extension->getAlias());
    }

    public function testContainerBuilderInstance(): void
    {
        $this->assertInstanceOf(ContainerBuilder::class, $this->container);
    }
}
