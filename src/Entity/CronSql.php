<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineCronJobBundle\Repository\CronSqlRepository;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineSnowflakeBundle\Traits\SnowflakeKeyAware;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

#[ORM\Table(name: 'cron_sql', options: ['comment' => '定时SQL'])]
#[ORM\Entity(repositoryClass: CronSqlRepository::class)]
class CronSql implements \Stringable
{
    use SnowflakeKeyAware;
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Column(length: 255, options: ['comment' => '标题'])]
    #[Assert\Length(max: 255)]
    private string $title = '';

    #[ORM\Column(type: Types::TEXT, options: ['comment' => 'SQL语句'])]
    #[Assert\Length(max: 65535)]
    private string $sqlStatement = '';

    #[ORM\Column(length: 60, options: ['comment' => '定时表达式'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 60)]
    private string $cronExpression = '* * * * *';

    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    public function __toString(): string
    {
        if (null === $this->getId() || '' === $this->getId()) {
            return '';
        }

        return $this->title;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getSqlStatement(): string
    {
        return $this->sqlStatement;
    }

    public function setSqlStatement(string $sqlStatement): void
    {
        $this->sqlStatement = $sqlStatement;
    }

    public function getCronExpression(): string
    {
        return $this->cronExpression;
    }

    public function setCronExpression(string $cronExpression): void
    {
        $this->cronExpression = $cronExpression;
    }
}
