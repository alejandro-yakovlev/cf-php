<?php

declare(strict_types=1);

namespace ColFil;

final readonly class CollaborativeFiltering
{
    public function __construct(private CFStrategyInterface $strategy)
    {
    }

    /**
     * @return CFRecommendation[]
     */
    public function getRecommendations(string $targetUserId, int $minRank, int $max): array
    {
        return $this->strategy->getRecommendations($targetUserId, $minRank, $max);
    }
}
