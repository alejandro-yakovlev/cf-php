<?php

declare(strict_types=1);

namespace ColFil\SimStrategy;

use ColFil\SimStrategyInterface;
use MathPHP\Statistics\Distance;

class EuclideanSimStrategy implements SimStrategyInterface
{
    public function simDistance(array $v1, array $v2): float
    {
        /**
         * Расстояние, вычисленное по этой формуле, будет тем меньше, чем больше сходства между людьми.
         * Однако нам нужна функция, значе- ние которой тем больше, чем люди более похожи друг на друга.
         * Этого можно добиться, добавив к вычисленному расстоянию 1 (чтобы никог- да не делить на 0) и взяв обратную величину.
         * Новая функция всегда возвращает значение от 0 до 1, причем 1 получа- ется, когда предпочтения двух людей в точности совпадают.
         */
        return 1 / (1 + Distance::euclidean($v1, $v2));
    }
}
