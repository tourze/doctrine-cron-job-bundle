<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\Attribute\When;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;

#[When(env: 'test')]
#[When(env: 'dev')]
class CronSqlFixtures extends Fixture implements FixtureGroupInterface
{
    public const DAILY_CLEANUP_SQL_REFERENCE = 'daily-cleanup-sql';
    public const WEEKLY_STATS_SQL_REFERENCE = 'weekly-stats-sql';
    public const MONTHLY_ARCHIVE_SQL_REFERENCE = 'monthly-archive-sql';

    public function load(ObjectManager $manager): void
    {
        // 1. 每日清理SQL
        $dailyCleanupSql = new CronSql();
        $dailyCleanupSql->setTitle('清理过期会话');
        $dailyCleanupSql->setSqlStatement('DELETE FROM sessions WHERE last_activity < DATE_SUB(NOW(), INTERVAL 30 DAY)');
        $dailyCleanupSql->setCronExpression('0 3 * * *');
        $dailyCleanupSql->setValid(true);

        $manager->persist($dailyCleanupSql);
        $this->addReference(self::DAILY_CLEANUP_SQL_REFERENCE, $dailyCleanupSql);

        // 2. 每周统计SQL
        $weeklyStatsSql = new CronSql();
        $weeklyStatsSql->setTitle('更新周统计报表');
        $weeklyStatsSql->setSqlStatement('CALL update_weekly_stats()');
        $weeklyStatsSql->setCronExpression('0 0 * * 1');
        $weeklyStatsSql->setValid(true);

        $manager->persist($weeklyStatsSql);
        $this->addReference(self::WEEKLY_STATS_SQL_REFERENCE, $weeklyStatsSql);

        // 3. 每月归档SQL
        $monthlyArchiveSql = new CronSql();
        $monthlyArchiveSql->setTitle('归档历史数据');
        $monthlyArchiveSql->setSqlStatement('INSERT INTO archive_logs SELECT * FROM logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 3 MONTH)');
        $monthlyArchiveSql->setCronExpression('0 1 1 * *');
        $monthlyArchiveSql->setValid(false);

        $manager->persist($monthlyArchiveSql);
        $this->addReference(self::MONTHLY_ARCHIVE_SQL_REFERENCE, $monthlyArchiveSql);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['cron_sql', 'doctrine_cron'];
    }
}
