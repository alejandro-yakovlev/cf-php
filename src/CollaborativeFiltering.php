<?php

declare(strict_types=1);

namespace CFPHP;

/**
 * Collaborative filtering.
 */
final readonly class CollaborativeFiltering
{
    public function __construct(private RecommendationStrategyInterface $strategy)
    {
    }

    /**
     * @return array<string, float>
     */
    public function getRecommendations(string $targetUserId): array
    {
        return $this->strategy->getRecommendations($targetUserId);
    }
}
