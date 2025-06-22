<?php

namespace Tourze\DoctrineCronJobBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Service\SnowflakeIdGenerator;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

#[ORM\Table(name: 'cron_sql', options: ['comment' => '定时SQL'])]
#[ORM\Entity(repositoryClass: CronSqlRepository::class)]
class CronSql implements \Stringable
{
    use TimestampableAware;
    use BlameableAware;
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(SnowflakeIdGenerator::class)]
    #[ORM\Column(type: Types::BIGINT, nullable: false, options: ['comment' => 'ID'])]
    private ?string $id = null;

    #[ORM\Column(length: 255, options: ['comment' => '标题'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, options: ['comment' => 'SQL语句'])]
    private ?string $sqlStatement = null;

    #[ORM\Column(length: 60, options: ['comment' => '定时表达式'])]
    private string $cronExpression = '* * * * *';

    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    private ?bool $valid = false;

    public function __toString(): string
    {
        if ($this->getId() === null || $this->getId() === '') {
            return '';
        }

        return $this->title ?? '';
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): self
    {
        $this->valid = $valid;

        return $this;
    }


    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getSqlStatement(): ?string
    {
        return $this->sqlStatement;
    }

    public function setSqlStatement(string $sqlStatement): static
    {
        $this->sqlStatement = $sqlStatement;

        return $this;
    }

    public function getCronExpression(): string
    {
        return $this->cronExpression;
    }

    public function setCronExpression(string $cronExpression): static
    {
        $this->cronExpression = $cronExpression;

        return $this;
    }}
