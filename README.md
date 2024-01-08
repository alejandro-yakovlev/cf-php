# PHP Collaborative Filtering (CF)

## Introduction
This library is a PHP implementation of the [collaborative filtering (CF)](https://en.wikipedia.org/wiki/Collaborative_filtering).

## Setup

```bash
composer require alejandro-yakovlev/cf-php
```

### Requirements
- PHP >= 8.2

## Usage

```php
<?php

use CFPHP\CollaborativeFiltering;
use CFPHP\RecommendationStrategy\UserBasedRecommendationStrategy;
use CFPHP\SimilarityStrategy\CosineSimilarityStrategy;
use CFPHP\User;

require_once __DIR__ . '/vendor/autoload.php';

$user1 = new User('user1', [
    'item1' => 5,
    'item2' => 5,
    'item3' => 5,
]);

$user2 = new User('user2', [
    'item1' => 4,
    'item2' => 4,
    'item3' => 4,
]);

$user3 = new User('user3', [
    'item1' => 3,
    'item2' => 3,
]);

$users = [
    $user1,
    $user2,
    $user3,
];

$similarityStrategy = new CosineSimilarityStrategy();
$recommendationStrategy = new UserBasedRecommendationStrategy($users, $similarityStrategy);

$collaborativeFiltering = new CollaborativeFiltering($recommendationStrategy);
$recommendations = $collaborativeFiltering->getRecommendations('user3');

print_r($recommendations);
```

Output:

```
Array
(
    [item3] => 4.5
)
```
