<?php

declare(strict_types=1);

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
    private DoctrineCronJobExtension $extension;

    private ContainerBuilder $container;

    protected function setUp(): void
    {
        parent::setUp();

        $this->extension = new DoctrineCronJobExtension();
        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.environment', 'test');
    }

    public function testLoadWithEmptyConfigs(): void
    {
        $this->extension->load([], $this->container);

        $this->assertInstanceOf(ContainerBuilder::class, $this->container);
    }

    public function testExtensionAlias(): void
    {
        $this->assertSame('doctrine_cron_job', $this->extension->getAlias());
    }

    public function testExtensionLoadsWithValidConfig(): void
    {
        $configs = [
            [
                'some_config' => 'some_value',
            ],
        ];

        $this->extension->load($configs, $this->container);

        $this->assertGreaterThan(0, $this->container->getDefinitions());
    }

    public function testGetNamespace(): void
    {
        $namespace = $this->extension->getNamespace();
        $this->assertIsString($namespace);
        $this->assertNotEmpty($namespace);
    }
}
