<?php

namespace Tourze\DoctrineCronJobBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;

/**
 * @method CronSql|null find($id, $lockMode = null, $lockVersion = null)
 * @method CronSql|null findOneBy(array $criteria, array $orderBy = null)
 * @method CronSql[]    findAll()
 * @method CronSql[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CronSqlRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CronSql::class);
    }
}
