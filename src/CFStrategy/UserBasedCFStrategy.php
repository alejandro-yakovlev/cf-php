<?php

declare(strict_types=1);

namespace ColFil\CFStrategy;

use ColFil\CFRecommendation;
use ColFil\CFStrategyInterface;
use ColFil\CFUser;
use ColFil\SimStrategyInterface;

final readonly class UserBasedCFStrategy implements CFStrategyInterface
{
    /**
     * @param array<string|int, CFUser> $users
     */
    public function __construct(private array $users, private SimStrategyInterface $simStrategy)
    {
    }

    public function getRecommendations(string $targetUserId, int $minRank, int $max): array
    {
        $targetUser = $this->getUser($targetUserId);

        $simCoefficients = $this->getSimCoefficients($targetUser);

        // Взвешенная оценка = Оценка, данная предмету другим пользователем * Коэффициент подобия с пользователем
        // Смысл в том, чтобы мнение пользователя с более похожими вкусами вносило больший вклад в общую оценку
        /** @var array<string, float> $sumWeightedRatings Сумма взвешенных оценок */
        $sumWeightedRatings = [];

        // Сумма коэффициентов подобия
        /** @var array<string, float> $sumWeightedRatings Сумма коэффициентов подобия с пользователями */
        $sumSimCoefficients = [];

        foreach ($this->users as $otherUser) {
            // Здесь мы в цикле обходим всех людей, кроме целевого пользователя
            if ($targetUser->isEqualTo($otherUser)) {
                continue;
            }

            // Проверяем, есть ли коэффициент подобия с пользователем.
            $simCoefficient = $simCoefficients[$otherUser->userId] ?? null;
            if (!$simCoefficient) {
                continue;
            }

            foreach ($otherUser->items as $itemId => $rating) {
                // Не сравнивать предметы, которые пользователь уже оценил
                if ($targetUser->hasItem($itemId)) {
                    continue;
                }

                // Сумма взвешенных оценок
                $currentSum = $sumWeightedRatings[$itemId] ?? 0;
                $sumWeightedRatings[$itemId] = $currentSum + $rating * $simCoefficient;

                // Сумма коэффициентов подобия
                $currentSum = $sumSimCoefficients[$itemId] ?? 0;
                $sumSimCoefficients[$itemId] = $currentSum + $simCoefficient;
            }
        }

        foreach ($sumWeightedRatings as $itemId => &$weightedRatingValue) {
            // Избавляемся от проблемы, когда образцы, с которыми взаимодействует
            // большее кол-во пользователей получают большее преимущество.
            // Для этого сумму взвешенных оценок пользователей делим на сумму коэффициентов подобия с пользователями.
            $weightedRatingValue /= $sumSimCoefficients[$itemId];
        }

        $recommendations = [];
        foreach ($sumWeightedRatings as $itemId => $rank) {
            // Если прогнозируемая оценка меньше минимальной, то не рекомендуем.
            if ($rank < $minRank) {
                continue;
            }
            $recommendations[] = new CFRecommendation($targetUser->userId, $itemId, $rank);
        }

        // Сортируем список по убыванию оценок.
        usort(
            $recommendations,
            fn (CFRecommendation $a, CFRecommendation $b) => $b->rank <=> $a->rank
        );

        // Ограничиваем список заданным кол-вом элементов.
        $recommendations = array_slice($recommendations, 0, $max);

        return $recommendations;
    }

    /**
     * Получить коэффициенты подобия с пользователями.
     * Коэффициент подобия - это мера близости между двумя пользователями.
     * Чем ближе пользователи, тем больше коэффициент подобия.
     *
     * @return array<string, float>
     */
    private function getSimCoefficients(CFUser $targetUser): array
    {
        $simCoefficients = [];
        foreach ($this->users as $otherUser) {
            $targetUserItemRatingVector = [];
            $otherUserItemRatingVector = [];
            // Формируем векторы оценок, данных одинаковым объектам.
            foreach ($otherUser->items as $otherItemId => $otherItemRating) {
                if ($targetUser->hasItem($otherItemId)) {
                    $targetUserItemRatingVector[] = $targetUser->items[$otherItemId];
                    $otherUserItemRatingVector[] = $otherItemRating;
                }
            }

            if (count($targetUserItemRatingVector) <= 1) {
                continue;
            }

            // Вычисляем расстояние между векторами оценок.
            $simCoefficients[$otherUser->userId] = $this->simStrategy->simDistance(
                $targetUserItemRatingVector,
                $otherUserItemRatingVector
            );
        }

        // Сортируем по убыванию коэффициента подобия.
        uasort($simCoefficients, fn (float $a, float $b) => $b <=> $a);

        return $simCoefficients;
    }

    private function getUser(string $userId): CFUser
    {
        // Пользователь должен быть в списке пользователей.
        if (!isset($this->users[$userId])) {
            throw new \InvalidArgumentException(sprintf('User with id "%s" not found', $userId));
        }

        return $this->users[$userId];
    }
}
