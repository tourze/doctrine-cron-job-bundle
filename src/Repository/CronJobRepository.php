<?php

namespace Tourze\DoctrineCronJobBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;

/**
 * @method CronJob|null find($id, $lockMode = null, $lockVersion = null)
 * @method CronJob|null findOneBy(array $criteria, array $orderBy = null)
 * @method CronJob[]    findAll()
 * @method CronJob[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CronJobRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CronJob::class);
    }
}
