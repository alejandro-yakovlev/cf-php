<?php

declare(strict_types=1);

namespace ColFil;

class CFRecommendation
{
    public function __construct(public string $userId, public string $itemId, public float $rank)
    {
    }
}
