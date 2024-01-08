<?php

declare(strict_types=1);

namespace CFPHP\SimilarityStrategy;

use CFPHP\SimilarityStrategyInterface;
use MathPHP\Statistics\Distance;

class EuclideanSimilarityStrategy implements SimilarityStrategyInterface
{
    public function simDistance(array $v1, array $v2): float
    {
        /**
         * EN: The distance calculated by this formula will be the smaller, the more similar the people are.
         * However, we need a function whose value is the greater, the more similar people are to each other.
         * This can be achieved by adding 1 to the calculated distance (so as not to divide by 0) and taking the inverse.
         * The new function always returns a value from 0 to 1, and 1 is obtained when the preferences of two people exactly match.
         */
        return Distance::euclidean($v1, $v2);
    }
}
