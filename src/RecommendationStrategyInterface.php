<?php

declare(strict_types=1);

namespace CFPHP;

/**
 * Strategy for recommending items to a person.
 */
interface RecommendationStrategyInterface
{
    /**
     * Get recommendations for a given person.
     *
     * @return array<string, float> Example: ['item1' => 3.5, 'item2' => 4.3]
     *
     * @throws \InvalidArgumentException
     */
    public function getRecommendations(string $targetUserId): array;
}
