<?php

declare(strict_types=1);

namespace ColFil\SimStrategy;

use ColFil\SimStrategyInterface;
use MathPHP\Statistics\Distance;

class CosSimStrategy implements SimStrategyInterface
{
    public function simDistance(array $v1, array $v2): float
    {
        return Distance::cosineSimilarity($v1, $v2);
    }
}
