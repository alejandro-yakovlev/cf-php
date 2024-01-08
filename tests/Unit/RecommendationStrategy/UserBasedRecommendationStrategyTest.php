<?php

use CFPHP\RecommendationStrategy\UserBasedRecommendationStrategy;
use CFPHP\SimilarityStrategy\CosineSimilarityStrategy;
use CFPHP\User;

beforeEach(function () {
    $this->dataset = [
        'Senior' => [
            'PHP' => 5.0,
            'Nginx' => 4.0,
            'Docker' => 3.0,
            'Xdebug' => 5.0,
            'TDD' => 5.0,
            'Clean Architecture' => 1.0,
            'Time Management' => 1.0,
            'Stress Management' => 1.0,
            'Public Speaking Skill' => 1.0,
        ],
        'Middle' => [
            'PHP' => 4.0,
            'Nginx' => 3.0,
            'Docker' => 3.0,
            'Xdebug' => 4.0,
            'TDD' => 5.0,
            'Time Management' => 3.0,
        ],
        'Junior' => [
            'PHP' => 3.0,
            'Nginx' => 2.0,
            'Public Speaking Skill' => 1.0,
        ],
    ];
});

it('user based strategy', function () {
    $users = [];
    foreach ($this->dataset as $userId => $items) {
        $users[$userId] = new User($userId, $items);
    }
    $strategy = new UserBasedRecommendationStrategy($users, new CosineSimilarityStrategy());
    expect(count($strategy->getRecommendations('Middle')))->toBe(3);
});
