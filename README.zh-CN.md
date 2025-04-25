# Doctrine CronJob Bundle

[English](README.md) | [中文](README.zh-CN.md)

这个 bundle 提供了在 Symfony 项目中使用 Doctrine 来管理定时任务的功能。

## 功能特性

- 支持通过数据库管理定时任务
- 支持定时执行 SQL 查询
- 集成 Symfony 定时任务系统
- 提供实体管理和仓储服务

## 安装

```bash
composer require tourze/doctrine-cron-job-bundle
```

## 快速开始

1. 注册 Bundle

```php
// config/bundles.php
return [
    // ...
    Tourze\DoctrineCronJobBundle\DoctrineCronJobBundle::class => ['all' => true],
];
```

2. 创建 CronJob 实体

```php
use Tourze\DoctrineCronJobBundle\Entity\CronJob;

$job = new CronJob();
$job->setName('my-job');
$job->setCommand('php bin/console app:my-command');
$job->setSchedule('* * * * *'); // 每分钟执行
$job->setDescription('我的定时任务');
$job->setValid(true);

// 保存到数据库
$entityManager->persist($job);
$entityManager->flush();
```

3. 创建 CronSql 实体

```php
use Tourze\DoctrineCronJobBundle\Entity\CronSql;

$cronSql = new CronSql();
$cronSql->setTitle('定时统计');
$cronSql->setSqlStatement('SELECT COUNT(*) FROM users');
$cronSql->setCronExpression('0 0 * * *'); // 每天零点执行
$cronSql->setValid(true);

// 保存到数据库
$entityManager->persist($cronSql);
$entityManager->flush();
```

## 单元测试

该项目包含完整的单元测试套件，所有测试均已通过:

```bash
./vendor/bin/phpunit packages/doctrine-cron-job-bundle/tests
```

## 测试计划状态

以下测试已完成并通过:

- [x] 实体测试
  - [x] CronJob 实体测试 (100% 通过)
  - [x] CronSql 实体测试 (100% 通过)
- [x] 提供者测试
  - [x] DoctrineProvider 测试 (100% 通过)
  - [x] CronSqlProvider 测试 (100% 通过)
- [x] 依赖注入测试
  - [x] DoctrineCronJobExtension 测试 (100% 通过)
- [x] Bundle 测试
  - [x] DoctrineCronJobBundle 测试 (100% 通过)

## 许可证

MIT 许可证。详见 [LICENSE](LICENSE) 文件。 