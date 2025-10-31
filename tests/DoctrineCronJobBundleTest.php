<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Tourze\DoctrineCronJobBundle\DoctrineCronJobBundle;
use Tourze\PHPUnitSymfonyKernelTest\AbstractBundleTestCase;

/**
 * @internal
 */
#[CoversClass(DoctrineCronJobBundle::class)]
#[RunTestsInSeparateProcesses]
final class DoctrineCronJobBundleTest extends AbstractBundleTestCase
{
}
