<?php

namespace Tourze\DoctrineCronJobBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\DoctrineCronJobBundle\DoctrineCronJobBundle;

class DoctrineCronJobBundleTest extends TestCase
{
    public function testBundleInstance(): void
    {
        $bundle = new DoctrineCronJobBundle();

        // 验证继承自 Bundle 类
        $this->assertInstanceOf(Bundle::class, $bundle);
    }

    public function testGetPath(): void
    {
        $bundle = new DoctrineCronJobBundle();
        $bundlePath = $bundle->getPath();

        // 使用反射获取实际的Bundle类路径
        $reflector = new \ReflectionClass(DoctrineCronJobBundle::class);
        $expectedPath = dirname($reflector->getFileName());

        $this->assertEquals($expectedPath, $bundlePath);
    }
}
