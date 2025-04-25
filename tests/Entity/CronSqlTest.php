<?php

namespace Tourze\DoctrineCronJobBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use Tourze\DoctrineCronJobBundle\Entity\CronSql;

class CronSqlTest extends TestCase
{
    public function testEntityGettersAndSetters(): void
    {
        $cronSql = new CronSql();

        // 测试标题
        $cronSql->setTitle('测试SQL任务');
        $this->assertEquals('测试SQL任务', $cronSql->getTitle());

        // 测试SQL语句
        $sql = 'SELECT * FROM test_table';
        $cronSql->setSqlStatement($sql);
        $this->assertEquals($sql, $cronSql->getSqlStatement());

        // 测试Cron表达式
        $cronExpression = '0 */2 * * *';
        $cronSql->setCronExpression($cronExpression);
        $this->assertEquals($cronExpression, $cronSql->getCronExpression());

        // 测试默认Cron表达式
        $newCronSql = new CronSql();
        $this->assertEquals('* * * * *', $newCronSql->getCronExpression());

        // 测试有效状态
        $cronSql->setValid(true);
        $this->assertTrue($cronSql->isValid());

        // 测试ID
        $this->assertEquals('0', $cronSql->getId());

        // 测试创建人
        $cronSql->setCreatedBy('admin');
        $this->assertEquals('admin', $cronSql->getCreatedBy());

        // 测试更新人
        $cronSql->setUpdatedBy('admin');
        $this->assertEquals('admin', $cronSql->getUpdatedBy());

        // 测试创建时间
        $time = new \DateTime();
        $cronSql->setCreateTime($time);
        $this->assertSame($time, $cronSql->getCreateTime());

        // 测试更新时间
        $time = new \DateTime();
        $cronSql->setUpdateTime($time);
        $this->assertSame($time, $cronSql->getUpdateTime());
    }
}
