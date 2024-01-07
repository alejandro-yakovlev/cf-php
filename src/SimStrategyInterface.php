<?php

declare(strict_types=1);

namespace ColFil;

interface SimStrategyInterface
{
    public function simDistance(array $v1, array $v2): float;

}
