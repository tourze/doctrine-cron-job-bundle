<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\DoctrineCronJobBundle\Entity\CronJob;

#[When(env: 'test')]
#[When(env: 'dev')]
class CronJobFixtures extends Fixture implements FixtureGroupInterface
{
    public const DAILY_BACKUP_JOB_REFERENCE = 'daily-backup-job';
    public const WEEKLY_CLEANUP_JOB_REFERENCE = 'weekly-cleanup-job';
    public const HOURLY_STATS_JOB_REFERENCE = 'hourly-stats-job';

    public function load(ObjectManager $manager): void
    {
        // 1. 每日备份任务
        $dailyBackupJob = new CronJob();
        $dailyBackupJob->setName('数据库每日备份');
        $dailyBackupJob->setCommand('bin/console app:backup:database');
        $dailyBackupJob->setSchedule('0 2 * * *');
        $dailyBackupJob->setDescription('每天凌晨2点执行数据库备份任务');
        $dailyBackupJob->setValid(true);

        $manager->persist($dailyBackupJob);
        $this->addReference(self::DAILY_BACKUP_JOB_REFERENCE, $dailyBackupJob);

        // 2. 每周清理任务
        $weeklyCleanupJob = new CronJob();
        $weeklyCleanupJob->setName('日志文件清理');
        $weeklyCleanupJob->setCommand('bin/console app:logs:cleanup');
        $weeklyCleanupJob->setSchedule('0 1 * * 0');
        $weeklyCleanupJob->setDescription('每周日凌晨1点清理过期日志文件');
        $weeklyCleanupJob->setValid(true);

        $manager->persist($weeklyCleanupJob);
        $this->addReference(self::WEEKLY_CLEANUP_JOB_REFERENCE, $weeklyCleanupJob);

        // 3. 每小时统计任务
        $hourlyStatsJob = new CronJob();
        $hourlyStatsJob->setName('小时统计更新');
        $hourlyStatsJob->setCommand('bin/console app:stats:hourly');
        $hourlyStatsJob->setSchedule('0 * * * *');
        $hourlyStatsJob->setDescription('每小时更新系统统计数据');
        $hourlyStatsJob->setValid(true);

        $manager->persist($hourlyStatsJob);
        $this->addReference(self::HOURLY_STATS_JOB_REFERENCE, $hourlyStatsJob);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['cron_job', 'doctrine_cron'];
    }
}
