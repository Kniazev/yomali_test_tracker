<?php

namespace App\Tracker\Controller;

use App\Tracker\Application\PageViewService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageViewController
{
    public function __construct(private PageViewService $service) {}

    #[Route('/api/track', name: 'track_page_view', methods: ['POST'])]
    public function track(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $this->service->save(
            $data['url'] ?? '/',
            $data['visitorId'] ?? 'unknown',
            $request->headers->get('User-Agent') ?? 'unknown',
            $request->getClientIp() ?? '0.0.0.0'
        );

        return new JsonResponse(['status' => 'ok']);
    }

    #[Route('/api/stats', name: 'page_view_stats', methods: ['GET'])]
    public function stats(Request $request): JsonResponse
    {
        try {
            $from = new \DateTimeImmutable($request->query->get('from'));
            $to = new \DateTimeImmutable($request->query->get('to'));
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Invalid date format'], 400);
        }

        $data = $this->service->getUniqueVisitsPerPage($from, $to);

        return new JsonResponse($data);
    }
}