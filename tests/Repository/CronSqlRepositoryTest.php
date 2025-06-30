<?php

namespace Tourze\DoctrineCronJobBundle\Test\Repository;

use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;

class CronSqlRepositoryTest extends TestCase
{
    public function testConstruct(): void
    {
        $registry = $this->createMock(ManagerRegistry::class);
        $repository = new CronSqlRepository($registry);
        
        $this->assertInstanceOf(CronSqlRepository::class, $repository);
    }
}