<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\NewsEntryCreated;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendNotificationsOnNewsEntryCreated
{
    public function __invoke(NewsEntryCreated $newsEntryCreated): void
    {
        // do something
    }

}