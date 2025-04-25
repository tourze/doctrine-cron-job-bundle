# Doctrine CronJob Bundle

[English](README.md) | [中文](README.zh-CN.md)

This bundle provides functionality for managing cron jobs using Doctrine in Symfony projects.

## Features

- Support for database-managed cron jobs
- Support for scheduled SQL query execution
- Integration with Symfony cron job system
- Entity management and repository services

## Installation

```bash
composer require tourze/doctrine-cron-job-bundle
```

## Usage

1. Register the Bundle

```php
// config/bundles.php
return [
    // ...
    Tourze\DoctrineCronJobBundle\DoctrineCronJobBundle::class => ['all' => true],
];
```

2. Create a CronJob entity

```php
use Tourze\DoctrineCronJobBundle\Entity\CronJob;

$job = new CronJob();
$job->setName('my-job');
$job->setCommand('php bin/console app:my-command');
$job->setSchedule('* * * * *'); // Run every minute
$job->setDescription('My cron job');
$job->setValid(true);

// Save to database
$entityManager->persist($job);
$entityManager->flush();
```

3. Create a CronSql entity

```php
use Tourze\DoctrineCronJobBundle\Entity\CronSql;

$cronSql = new CronSql();
$cronSql->setTitle('Statistics Task');
$cronSql->setSqlStatement('SELECT COUNT(*) FROM users');
$cronSql->setCronExpression('0 0 * * *'); // Run at midnight every day
$cronSql->setValid(true);

// Save to database
$entityManager->persist($cronSql);
$entityManager->flush();
```

## Unit Tests

The project includes a complete unit test suite with all tests passing:

```bash
./vendor/bin/phpunit packages/doctrine-cron-job-bundle/tests
```

## Test Plan Status

The following tests have been completed and passed:

- [x] Entity Tests
  - [x] CronJob Entity Test (100% passed)
  - [x] CronSql Entity Test (100% passed)
- [x] Provider Tests
  - [x] DoctrineProvider Test (100% passed)
  - [x] CronSqlProvider Test (100% passed)
- [x] Dependency Injection Tests
  - [x] DoctrineCronJobExtension Test (100% passed)
- [x] Bundle Tests
  - [x] DoctrineCronJobBundle Test (100% passed)

## License

MIT License. See [LICENSE](LICENSE) file for more information.
