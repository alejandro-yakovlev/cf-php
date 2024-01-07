<?php

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
    $strategy = new CosSimStrategy();
    $v1 = array_intersect_key($this->dataset['Senior'], $this->dataset['Middle']);
    $v2 = array_intersect_key($this->dataset['Middle'], $this->dataset['Senior']);
    expect($strategy->simDistance($v1, $v2))->toBe(0.966250377775183);
});
