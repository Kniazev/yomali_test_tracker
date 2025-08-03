<?php
namespace App\Tracker\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'page_views')]
class PageView
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 2048)]
    private string $url;

    #[ORM\Column(type: 'string', length: 255)]
    private string $visitorId;

    #[ORM\Column(type: 'string', length: 255)]
    private string $userAgent;

    #[ORM\Column(type: 'string', length: 45)]
    private string $ipAddress;

    #[ORM\Column(type: 'datetime_immutable')]
    private \DateTimeImmutable $visitedAt;

    public function __construct(
        string $url,
        string $visitorId,
        string $userAgent,
        string $ipAddress
    ) {
        $this->url = $url;
        $this->visitorId = $visitorId;
        $this->userAgent = $userAgent;
        $this->ipAddress = $ipAddress;
        $this->visitedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getVisitorId(): string
    {
        return $this->visitorId;
    }

    public function setVisitorId(string $visitorId): void
    {
        $this->visitorId = $visitorId;
    }

    public function getUserAgent(): string
    {
        return $this->userAgent;
    }

    public function setUserAgent(string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    public function setIpAddress(string $ipAddress): void
    {
        $this->ipAddress = $ipAddress;
    }

    public function getVisitedAt(): \DateTimeImmutable
    {
        return $this->visitedAt;
    }

    public function setVisitedAt(\DateTimeImmutable $visitedAt): void
    {
        $this->visitedAt = $visitedAt;
    }
}