<?php

declare(strict_types=1);

namespace App\EventHandlers;

use App\Events\UserCreated;
use App\Repository\ChatsRepository;
use App\Repository\ConfirmationCodeRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class SendVerificationCodeOnUserCreated
{
    public function __construct(
        private string $telegramToken,
        private ConfirmationCodeRepository $confirmationCodeRepository,
        private ChatsRepository $chatsRepository,
    ) {
    }

    public function __invoke(UserCreated $userCreated): void
    {
        if ($userCreated->userId === null) {
            return;
        }

        $confirmationCode = $this->confirmationCodeRepository->findOneByUserId($userCreated->userId);
        if ($confirmationCode === null) {
            return;
        }

        $chatsIterable = $this->chatsRepository->iterateOverAll();
        foreach ($chatsIterable as $chat) {
            file_get_contents(sprintf(
                'https://api.telegram.org/bot%s/sendMessage?chat_id=%s&text=%s',
                $this->telegramToken,
                $chat->getId(),
                $confirmationCode->getCode()
            ));
        }
    }
}