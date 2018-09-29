Associator PHP SDK
==================

### Installation

To install Associator PHP SDK, simply:
```
composer require tarnawski/associator-php-sdk
```

### Quick Start and Examples
```php
<?php

require __DIR__ . '/vendor/autoload.php';

use Associator\Associator;

$associator = new Associator('APPLICATION_KEY');

// Save single transaction items
$associator->saveTransaction(['3', '8', '16', '256']);

// Get array of associate items
$associatedItem = $associator->getAssociations(['3', '8', '16'], 5, 5);
```