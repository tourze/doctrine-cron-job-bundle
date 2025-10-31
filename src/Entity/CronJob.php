<?php

declare(strict_types=1);

namespace Tourze\DoctrineCronJobBundle\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Tourze\DoctrineCronJobBundle\Repository\CronJobRepository;
use Tourze\DoctrineIndexedBundle\Attribute\IndexColumn;
use Tourze\DoctrineTimestampBundle\Traits\TimestampableAware;
use Tourze\DoctrineTrackBundle\Attribute\TrackColumn;
use Tourze\DoctrineUserBundle\Traits\BlameableAware;

#[ORM\Table(name: 'cron_job', options: ['comment' => '定时任务'])]
#[ORM\Entity(repositoryClass: CronJobRepository::class)]
class CronJob implements \Stringable
{
    use TimestampableAware;
    use BlameableAware;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER, options: ['comment' => 'ID'])]
    private int $id = 0;

    #[ORM\Column(name: 'name', type: Types::STRING, length: 191, unique: true, options: ['comment' => '名称'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 191)]
    private string $name;

    #[ORM\Column(name: 'command', type: Types::STRING, length: 1024, options: ['comment' => '命令'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 1024)]
    private string $command;

    #[ORM\Column(name: 'schedule', type: Types::STRING, length: 191, options: ['comment' => '计划'])]
    #[Assert\NotBlank]
    #[Assert\Length(max: 191)]
    private string $schedule;

    #[ORM\Column(name: 'description', type: Types::TEXT, nullable: true, options: ['comment' => '描述'])]
    #[Assert\Length(max: 65535)]
    private ?string $description = null;

    #[IndexColumn]
    #[TrackColumn]
    #[ORM\Column(type: Types::BOOLEAN, nullable: true, options: ['comment' => '有效', 'default' => 0])]
    #[Assert\Type(type: 'bool')]
    private ?bool $valid = false;

    public function __toString(): string
    {
        if (0 === $this->getId()) {
            return '';
        }

        return $this->name;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function isValid(): ?bool
    {
        return $this->valid;
    }

    public function setValid(?bool $valid): void
    {
        $this->valid = $valid;
    }

    /**
     * 设置名称
     *
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * 获取名称
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $command
     */
    public function setCommand(string $command): void
    {
        $this->command = $command;
    }

    /**
     * @return string
     */
    public function getCommand(): string
    {
        return $this->command;
    }

    /**
     * @param string $schedule
     */
    public function setSchedule(string $schedule): void
    {
        $this->schedule = $schedule;
    }

    /**
     * @return string
     */
    public function getSchedule(): string
    {
        return $this->schedule;
    }

    /**
     * 设置描述
     *
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * 获取描述
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setTitle(string $title): void
    {
        $this->name = $title;
    }

    public function getTitle(): string
    {
        return $this->name;
    }
}
