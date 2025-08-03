<?php

namespace App\Tracker\Infrastructure\Repository;

use App\Tracker\Domain\Entity\PageView;
use App\Tracker\Domain\Repository\PageViewRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class PageViewRepository implements PageViewRepositoryInterface
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function save(PageView $pageView): void
    {
        $this->em->persist($pageView);
        $this->em->flush();
    }

    public function countUniqueVisitsGroupedByUrl(\DateTimeImmutable $from, \DateTimeImmutable $to): array
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('pv.url, COUNT(DISTINCT pv.visitorId) as unique_visits')
            ->from(PageView::class, 'pv')
            ->where('pv.visitedAt BETWEEN :from AND :to')
            ->setParameter('from', $from)
            ->setParameter('to', $to)
            ->groupBy('pv.url');

        return $qb->getQuery()->getArrayResult();
    }
}