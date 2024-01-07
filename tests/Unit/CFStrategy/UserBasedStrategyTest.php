<?php

use ColFil\CFStrategy\UserBasedCFStrategy;
use ColFil\CFUser;
use ColFil\SimStrategy\CosSimStrategy;

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

it('cosine similarity strategy', function () {
    $users = [];
    foreach ($this->dataset as $userId => $items) {
        $users[$userId] = new CFUser($userId, $items);
    }
    $strategy = new UserBasedCFStrategy($users, new CosSimStrategy());
    expect(count($strategy->getRecommendations('Middle', 1, 10)))->toBe(3);
});
