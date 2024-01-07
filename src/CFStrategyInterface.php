<?php

declare(strict_types=1);

namespace ColFil;

interface CFStrategyInterface
{
    /**
     * Получить рекомендации для заданного человека, пользуясь взвешенным средним оценок,
     * данных всеми остальными пользователями.
     *
     * @return CFRecommendation[]
     *
     * @throws \InvalidArgumentException
     */
    public function getRecommendations(string $targetUserId, int $minRank, int $max): array;
}
