<?php

namespace Tourze\DoctrineCronJobBundle\Provider;

use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;
use Tourze\Symfony\CronJob\Provider\CronCommandProvider;
use Tourze\Symfony\CronJob\Request\CommandRequest;

class CronSqlProvider implements CronCommandProvider
{
    public function __construct(private readonly CronSqlRepository $cronSqlRepository)
    {
    }

    public function getCommands(): iterable
    {
        foreach ($this->cronSqlRepository->findBy(['valid' => true]) as $job) {
            $r = new CommandRequest();
            $r->setCommand('dbal:run-sql');
            $r->setOptions([
                'sql' => $job->getSqlStatement(),
            ]);
            $r->setCronExpression($job->getCronExpression());
            yield $r;
        }
    }
}
