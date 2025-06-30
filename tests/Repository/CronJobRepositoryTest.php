<?php

namespace Tourze\DoctrineCronJobBundle\Test\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;

class CronJobRepositoryTest extends TestCase
{
    public function testConstruct(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new CronJobRepository($registry);
        
        $this->assertInstanceOf(CronJobRepository::class, $repository);
    }
}