<?php

declare(strict_types=1);

namespace CFPHP\RecommendationStrategy;

use CFPHP\RecommendationStrategyInterface;
use CFPHP\SimilarityStrategyInterface;
use CFPHP\User;
use MathPHP\Statistics\Average;

final readonly class UserBasedRecommendationStrategy implements RecommendationStrategyInterface
{
    /**
     * @param array<User> $users
     */
    public function __construct(private array $users, private SimilarityStrategyInterface $simStrategy)
    {
    }

    public function getRecommendations(string $targetUserId): array
    {
        $targetUser = $this->getUser($targetUserId);
        $simCoefficients = $this->getSimCoefficients($targetUser);

        /**
         * List of items that the user has not yet rated.
         * @var array<string, array{ratings: array<string, float>, weights: array<string, float>}> $items
         * */
        $items = [];

        foreach ($this->users as $otherUser) {
            // Check if there is a similarity coefficient with the user.
            $simCoefficient = $simCoefficients[$otherUser->userId] ?? null;
            if (!$simCoefficient) {
                continue;
            }

            foreach ($otherUser->items as $itemId => $rating) {
                // Do not compare items that the user has already rated
                if ($targetUser->hasItem($itemId)) {
                    continue;
                }

                // Form a list of items that the user has not yet rated.
                // For each item, we form a list of ratings and weights, so that we can then calculate the weighted rating.
                $items[$itemId]['ratings'][] = $rating ;
                $items[$itemId]['weights'][] = $simCoefficient;
            }
        }

        $recommendations = [];
        foreach ($items as $itemId => $item) {
            // Weighted arithmetic mean - the predicted rating of the user for the item.
            $recommendations[$itemId] = Average::weightedMean($item['ratings'], $item['weights']);
        }

        // Sort the recommendations in descending order.
        arsort($recommendations);

        return $recommendations;
    }

    /**
     * Get similarity coefficients with users.
     * The similarity coefficient is a measure of the closeness between two users.
     * The closer the users, the greater the similarity coefficient.
     * 0 - users are completely different.
     * 1 - users are completely the same.
     *
     * @return array<string, float>
     */
    public function getSimCoefficients(User $targetUser): array
    {
        $simCoefficients = [];
        foreach ($this->users as $otherUser) {
            $targetUserItemRatingVector = array_intersect_key($targetUser->items, $otherUser->items);
            if (count($targetUserItemRatingVector) <= 1) {
                continue;
            }

            $otherUserItemRatingVector = array_intersect_key($otherUser->items, $targetUser->items);

            // Calculate the distance between the rating vectors.
            $simCoefficients[$otherUser->userId] = $this->simStrategy->simDistance(
                $targetUserItemRatingVector,
                $otherUserItemRatingVector
            );
        }

        return $simCoefficients;
    }

    private function getUser(string $userId): User
    {
        foreach ($this->users as $user) {
            if ($user->userId === $userId) {
                return $user;
            }
        }

        throw new \InvalidArgumentException(sprintf('User with id "%s" not found', $userId));
    }
}
