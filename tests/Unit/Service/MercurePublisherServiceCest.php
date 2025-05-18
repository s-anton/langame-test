<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service;

use App\Mercure\Message\MessageInterface;
use App\Service\MercurePublisherService;
use App\Tests\Support\UnitTester;
use Codeception\Stub;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;

class MercurePublisherServiceCest
{
    public function testPublish(UnitTester $I): void
    {
        $hub = Stub::makeEmpty(
            HubInterface::class,
            ['publish' => Stub\Expected::once(function (Update $message) use ($I) {
                $I->assertEquals(
                    json_encode(['type' => 'any-type', 'data' => []]),
                    $message->getData()
                );
                return 'result';
            })]
        );

        $service = $this->getService($hub);
        $service->publish(new class implements MessageInterface
        {
            public function getType(): string
            {
                return 'any-type';
            }

            public function jsonSerialize(): mixed
            {
                return [];
            }
        });
    }

    private function getService(HubInterface $hub): MercurePublisherService
    {
        return new MercurePublisherService(
            hub: $hub,
            appDomain: 'any-domain'
        );
    }
}