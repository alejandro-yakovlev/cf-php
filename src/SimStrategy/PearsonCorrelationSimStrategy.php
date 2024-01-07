<?php

declare(strict_types=1);

namespace ColFil\SimStrategy;

use ColFil\SimStrategyInterface;
use MathPHP\Statistics\Correlation;

final class PearsonCorrelationSimStrategy implements SimStrategyInterface
{
    public function simDistance(array $v1, array $v2): float
    {
        return Correlation::r($v1, $v2);
    }
}
