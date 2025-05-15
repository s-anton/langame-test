<?php

namespace App\Mercure\Message;

interface MessageInterface extends \JsonSerializable
{
    public function getType(): string;
}