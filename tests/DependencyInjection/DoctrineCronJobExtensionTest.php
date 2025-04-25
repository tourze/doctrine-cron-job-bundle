<?php

namespace Tourze\DoctrineCronJobBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Tourze\DoctrineCronJobBundle\DependencyInjection\DoctrineCronJobExtension;

class DoctrineCronJobExtensionTest extends TestCase
{
    private ContainerBuilder $container;
    private DoctrineCronJobExtension $extension;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->extension = new DoctrineCronJobExtension();
    }

    public function testServicesLoaded(): void
    {
        // 加载扩展服务
        $this->extension->load([], $this->container);

        // 验证服务定义存在
        $this->assertTrue($this->container->hasDefinition('Tourze\DoctrineCronJobBundle\Provider\DoctrineProvider'));
        $this->assertTrue($this->container->hasDefinition('Tourze\DoctrineCronJobBundle\Provider\CronSqlProvider'));
        $this->assertTrue($this->container->hasDefinition('Tourze\DoctrineCronJobBundle\Repository\CronJobRepository'));
        $this->assertTrue($this->container->hasDefinition('Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository'));
    }

    public function testServiceConfiguration(): void
    {
        // 加载扩展服务
        $this->extension->load([], $this->container);

        // 检查服务自动配置和自动注入
        $services = [
            'Tourze\DoctrineCronJobBundle\Provider\DoctrineProvider',
            'Tourze\DoctrineCronJobBundle\Provider\CronSqlProvider',
            'Tourze\DoctrineCronJobBundle\Repository\CronJobRepository',
            'Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository'
        ];

        foreach ($services as $service) {
            $definition = $this->container->getDefinition($service);
            $this->assertTrue($definition->isAutowired());
            $this->assertTrue($definition->isAutoconfigured());
        }
    }
}
