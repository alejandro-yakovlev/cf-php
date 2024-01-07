<?php

declare(strict_types=1);

namespace ColFil;

class CFUser
{
    /**
     * @param array<string, float> $items
     */
    public function __construct(
        public readonly string $userId,
        public array $items = []
    ) {
    }

    public function isEqualTo(CFUser $otherUser): bool
    {
        return $this->userId === $otherUser->userId;
    }

    /**
     * Имеет ли пользователь оценку для объекта.
     */
    public function hasItem(string $itemId): bool
    {
        return isset($this->items[$itemId]);
    }
}
