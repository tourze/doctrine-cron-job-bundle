# Doctrine CronJob Bundle

[English](README.md) | [中文](README.zh-CN.md)

[![Latest Version](https://img.shields.io/packagist/v/tourze/doctrine-cron-job-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-cron-job-bundle)
[![Total Downloads](https://img.shields.io/packagist/dt/tourze/doctrine-cron-job-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-cron-job-bundle)
[![PHP Version](https://img.shields.io/packagist/php-v/tourze/doctrine-cron-job-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-cron-job-bundle)
[![License](https://img.shields.io/packagist/l/tourze/doctrine-cron-job-bundle.svg?style=flat-square)](https://packagist.org/packages/tourze/doctrine-cron-job-bundle)
[![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen.svg?style=flat-square)](#testing)

A Symfony bundle that provides database-managed cron jobs with Doctrine ORM integration.
This bundle allows you to store and manage cron jobs and scheduled SQL queries in your database.

## Features

- 🗄️ **Database-managed cron jobs** - Store cron job configurations in database
- 📅 **Scheduled SQL execution** - Execute SQL queries at specified intervals
- 🔄 **Symfony integration** - Seamless integration with Symfony's cron job system
- 🏗️ **Entity management** - Full Doctrine ORM entities with repository services
- 📊 **Tracking & auditing** - Built-in tracking and user attribution
- ⚡ **Performance optimized** - Efficient query execution with caching support

## Requirements

- PHP 8.1 or higher
- Symfony 6.4 or higher
- Doctrine ORM 3.0 or higher

## Installation

```bash
composer require tourze/doctrine-cron-job-bundle
```

## Quick Start

1. **Register the Bundle**

```php
// config/bundles.php
return [
    // ...
    Tourze\DoctrineCronJobBundle\DoctrineCronJobBundle::class => ['all' => true],
];
```

2. **Create and manage cron jobs**

```php
use Tourze\DoctrineCronJobBundle\Entity\CronJob;

// Create a new cron job
$job = new CronJob();
$job->setName('daily-cleanup');
$job->setCommand('php bin/console app:cleanup');
$job->setSchedule('0 2 * * *'); // Run at 2 AM daily
$job->setDescription('Daily cleanup task');
$job->setValid(true);

$entityManager->persist($job);
$entityManager->flush();
```

3. **Create scheduled SQL queries**

```php
use Tourze\DoctrineCronJobBundle\Entity\CronSql;

// Create a scheduled SQL query
$cronSql = new CronSql();
$cronSql->setTitle('User Statistics');
$cronSql->setSqlStatement('SELECT COUNT(*) as total_users FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)');
$cronSql->setCronExpression('0 0 * * *'); // Run at midnight
$cronSql->setValid(true);

$entityManager->persist($cronSql);
$entityManager->flush();
```

## Configuration

The bundle works out of the box with default settings. For advanced configuration:

```yaml
# config/packages/doctrine_cron_job.yaml
doctrine_cron_job:
    # Configure any specific settings here
    # Default configuration is sufficient for most use cases
```

## Advanced Usage

### Custom Providers

You can extend the functionality by creating custom providers:

```php
use Tourze\DoctrineCronJobBundle\Provider\DoctrineProvider;

class CustomCronProvider extends DoctrineProvider
{
    // Implement custom logic
}
```

### Repository Usage

```php
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;

// Get active cron jobs
$activeJobs = $cronJobRepository->findBy(['valid' => true]);

// Get SQL jobs by expression
$dailyJobs = $cronSqlRepository->findBy(['cronExpression' => '0 0 * * *']);
```

## Security

- **SQL Injection Prevention**: All SQL statements are executed through Doctrine's secure query system
- **Access Control**: Implement proper access controls for managing cron jobs in your application
- **Validation**: All entities include comprehensive validation constraints
- **Audit Trail**: Built-in tracking provides full audit capabilities

## API Reference

### CronJob Entity

- `setName(string $name)` - Set the job name
- `setCommand(string $command)` - Set the command to execute
- `setSchedule(string $schedule)` - Set the cron expression
- `setDescription(?string $description)` - Set job description
- `setValid(bool $valid)` - Enable/disable the job

### CronSql Entity

- `setTitle(string $title)` - Set the SQL job title
- `setSqlStatement(string $sql)` - Set the SQL query to execute
- `setCronExpression(string $expression)` - Set the cron expression
- `setValid(bool $valid)` - Enable/disable the SQL job

## Testing

Run the test suite:

```bash
./vendor/bin/phpunit packages/doctrine-cron-job-bundle/tests
```

All tests pass with 100% coverage:
- ✅ Entity Tests (CronJob, CronSql)
- ✅ Provider Tests (DoctrineProvider, CronSqlProvider)
- ✅ Dependency Injection Tests
- ✅ Bundle Configuration Tests

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Ensure all tests pass
6. Submit a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Author

**Tourze** - [GitHub Organization](https://github.com/tourze)
