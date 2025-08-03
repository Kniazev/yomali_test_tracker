<?php

namespace App\Tracker\Application;

use App\Tracker\Domain\Entity\PageView;
use App\Tracker\Domain\Repository\PageViewRepositoryInterface;

class PageViewService
{
    public function __construct(private PageViewRepositoryInterface $repository) {}

    public function save(string $url, string $visitorId, string $userAgent, string $ipAddress): void
    {
        $pageView = new PageView($url, $visitorId, $userAgent, $ipAddress);
        $this->repository->save($pageView);
    }

    public function getUniqueVisitsPerPage(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        return $this->repository->countUniqueVisitsGroupedByUrl($from, $to);
    }
}