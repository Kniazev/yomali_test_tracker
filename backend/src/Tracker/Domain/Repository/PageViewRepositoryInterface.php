<?php

namespace App\Tracker\Domain\Repository;

use App\Tracker\Domain\Entity\PageView;

interface PageViewRepositoryInterface
{
    public function save(PageView $pageView): void;
    public function countUniqueVisitsGroupedByUrl(\DateTimeImmutable $from, \DateTimeImmutable $to): array;
}