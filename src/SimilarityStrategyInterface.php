<?php

declare(strict_types=1);

namespace CFPHP;

/**
 * Strategy for calculating similarity between two vectors.
 */
interface SimilarityStrategyInterface
{
    /**
     * @param array<float> $v1
     * @param array<float> $v2
     */
    public function simDistance(array $v1, array $v2): float;

}
