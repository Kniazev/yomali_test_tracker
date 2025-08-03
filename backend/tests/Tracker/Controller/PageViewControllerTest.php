<?php

namespace tests\Tracker\Controller;

use App\Tracker\Domain\Entity\PageView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageViewControllerTest extends WebTestCase
{
    public function testTrackPageView(): void
    {
        $client = static::createClient();

        $client->request(
            'POST',
            '/api/track',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json', 'HTTP_USER_AGENT' => 'TestAgent'],
            json_encode([
                'url' => '/test-page',
                'visitorId' => 'test-visitor-id'
            ])
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());

        $responseData = json_decode($client->getResponse()->getContent(), true);
        $this->assertEquals('ok', $responseData['status']);
    }

    public function testStatsEndpointReturnsCorrectData(): void
    {
        $client = static::createClient();
        $container = static::getContainer();
        $em = $container->get(EntityManagerInterface::class);

        $connection = $em->getConnection();
        $platform = $connection->getDatabasePlatform();
        $connection->executeStatement($platform->getTruncateTableSQL('page_views', true));

        $views = [
            new PageView('/page-1', 'visitor-1', 'UA', '127.0.0.1'),
            new PageView('/page-1', 'visitor-2', 'UA', '127.0.0.2'),
            new PageView('/page-2', 'visitor-1', 'UA', '127.0.0.1'),
            new PageView('/page-2', 'visitor-1', 'UA', '127.0.0.1'),
        ];

        foreach ($views as $view) {
            $em->persist($view);
        }

        $em->flush();

        $from = (new \DateTimeImmutable('-1 day'))->format('Y-m-d');
        $to = (new \DateTimeImmutable('+1 day'))->format('Y-m-d');

        $client->request('GET', "/api/stats?from=$from&to=$to");

        $this->assertResponseIsSuccessful();
        $this->assertResponseFormatSame('json');

        $data = json_decode($client->getResponse()->getContent(), true);

        $this->assertIsArray($data);

        $expected = [
            ['url' => '/page-1', 'unique_visits' => 2],
            ['url' => '/page-2', 'unique_visits' => 1],
        ];

        $this->assertEqualsCanonicalizing($expected, $data);
    }
}
