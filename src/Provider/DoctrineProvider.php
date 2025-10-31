<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\Provider;

use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\Symfony\CronJob\Provider\CronCommandProvider;
use Tourze\Symfony\CronJob\Request\CommandRequest;

readonly class DoctrineProvider implements CronCommandProvider
{
    public function __construct(private CronJobRepository $jobRepository)
    {
    }

    public function getCommands(): iterable
    {
        foreach ($this->jobRepository->findBy(['valid' => true]) as $job) {
            $r = new CommandRequest();
            $r->setCommand($job->getCommand());
            $r->setCronExpression($job->getSchedule());
            yield $r;
        }
    }
}
