<?php

declare(strict_types=1);

namespace CFPHP;

class User
{
    /**
     * @param array<string, float> $items
     */
    public function __construct(
        public readonly string $userId,
        public array $items = []
    ) {
    }

    /**
     * Имеет ли пользователь оценку для объекта.
     */
    public function hasItem(string $itemId): bool
    {
        return isset($this->items[$itemId]);
    }
}
