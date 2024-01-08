<?php

declare(strict_types=1);

namespace CFPHP\SimilarityStrategy;

use CFPHP\SimilarityStrategyInterface;
use MathPHP\Statistics\Distance;

class CosineSimilarityStrategy implements SimilarityStrategyInterface
{
    public function simDistance(array $v1, array $v2): float
    {
        return Distance::cosineSimilarity($v1, $v2);
    }
}
