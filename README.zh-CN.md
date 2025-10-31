# Doctrine CronJob Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-cron-job-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-cron-job-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-cron-job-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-cron-job-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/doctrine-cron-job-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-cron-job-bundle)
[![License](https://img.shields.io/packagist/l/tourze/doctrine-cron-job-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-cron-job-bundle)
[![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)](#测试)

一个 Symfony bundle，提供基于数据库的定时任务管理功能，与 Doctrine ORM 深度集成。
该 bundle 允许您在数据库中存储和管理定时任务以及定时 SQL 查询。

## 功能特性

- 🗄️ **数据库管理定时任务** - 在数据库中存储定时任务配置
- 📅 **定时 SQL 执行** - 按指定间隔执行 SQL 查询
- 🔄 **Symfony 集成** - 与 Symfony 定时任务系统无缝集成
- 🏗️ **实体管理** - 完整的 Doctrine ORM 实体和仓储服务
- 📊 **追踪和审计** - 内置追踪和用户归属功能
- ⚡ **性能优化** - 高效的查询执行和缓存支持

## 系统要求

- PHP 8.1 或更高版本
- Symfony 6.4 或更高版本
- Doctrine ORM 3.0 或更高版本

## 安装

```bash
composer require tourze/doctrine-cron-job-bundle
```

## 快速开始

1. **注册 Bundle**

```php
// config/bundles.php
return [
    // ...
    Tourze\DoctrineCronJobBundle\DoctrineCronJobBundle::class => ['all' => true],
];
```

2. **创建和管理定时任务**

```php
use Tourze\DoctrineCronJobBundle\Entity\CronJob;

// 创建一个新的定时任务
$job = new CronJob();
$job->setName('daily-cleanup');
$job->setCommand('php bin/console app:cleanup');
$job->setSchedule('0 2 * * *'); // 每天凌晨2点执行
$job->setDescription('每日清理任务');
$job->setValid(true);

$entityManager->persist($job);
$entityManager->flush();
```

3. **创建定时 SQL 查询**

```php
use Tourze\DoctrineCronJobBundle\Entity\CronSql;

// 创建一个定时 SQL 查询
$cronSql = new CronSql();
$cronSql->setTitle('用户统计');
$cronSql->setSqlStatement('SELECT COUNT(*) as total_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)');
$cronSql->setCronExpression('0 0 * * *'); // 每天零点执行
$cronSql->setValid(true);

$entityManager->persist($cronSql);
$entityManager->flush();
```

## 配置

该 bundle 开箱即用，使用默认设置。高级配置选项：

```yaml
# config/packages/doctrine_cron_job.yaml
doctrine_cron_job:
    # 在此配置任何特定设置
    # 默认配置足以满足大多数使用场景
```

## 高级用法

### 自定义提供者

您可以通过创建自定义提供者来扩展功能：

```php
use Tourze\DoctrineCronJobBundle\Provider\DoctrineProvider;

class CustomCronProvider extends DoctrineProvider
{
    // 实现自定义逻辑
}
```

### 仓储使用

```php
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;

// 获取活跃的定时任务
$activeJobs = $cronJobRepository->findBy(['valid' => true]);

// 根据表达式获取 SQL 任务
$dailyJobs = $cronSqlRepository->findBy(['cronExpression' => '0 0 * * *']);
```

## 安全性

- **SQL 注入防护**：所有 SQL 语句都通过 Doctrine 的安全查询系统执行
- **访问控制**：为应用程序中的定时任务管理实现适当的访问控制
- **验证机制**：所有实体都包含全面的验证约束
- **审计跟踪**：内置跟踪提供完整的审计功能

## API 参考

### CronJob 实体

- `setName(string $name)` - 设置任务名称
- `setCommand(string $command)` - 设置要执行的命令
- `setSchedule(string $schedule)` - 设置 cron 表达式
- `setDescription(?string $description)` - 设置任务描述
- `setValid(bool $valid)` - 启用/禁用任务

### CronSql 实体

- `setTitle(string $title)` - 设置 SQL 任务标题
- `setSqlStatement(string $sql)` - 设置要执行的 SQL 查询
- `setCronExpression(string $expression)` - 设置 cron 表达式
- `setValid(bool $valid)` - 启用/禁用 SQL 任务

## 测试

运行测试套件：

```bash
./vendor/bin/phpunit packages/doctrine-cron-job-bundle/tests
```

所有测试均通过，100% 覆盖率：
- ✅ 实体测试 (CronJob, CronSql)
- ✅ 提供者测试 (DoctrineProvider, CronSqlProvider)
- ✅ 依赖注入测试
- ✅ Bundle 配置测试

## 贡献

1. Fork 本仓库
2. 创建功能分支
3. 进行您的更改
4. 为新功能添加测试
5. 确保所有测试通过
6. 提交 pull request

## 许可证

该项目采用 MIT 许可证 - 详情请查看 [LICENSE](LICENSE) 文件。

## 作者

**Tourze** - [GitHub 组织](https://github.com/tourze) 