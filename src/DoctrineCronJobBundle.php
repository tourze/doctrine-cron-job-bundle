<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Tourze\BundleDependency\BundleDependencyInterface;

class DoctrineCronJobBundle extends Bundle implements BundleDependencyInterface
{
    /**
     * @return array<class-string, array<string, bool>>
     */
    public static function getBundleDependencies(): array
    {
        return [
            DoctrineBundle::class => ['all' => true],
        ];
    }
}
