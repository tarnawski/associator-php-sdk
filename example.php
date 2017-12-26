<?php

use Associator\Associator;

include 'vendor/autoload.php';

$associator = new Associator('436c2444-9a0e-45cc-8808-1f6660345287');


var_dump($associator->getAssociations(['3', '8', '16'], 5, 5));exit;

// Save single transaction items
$associator->saveTransaction(['3', '8', '16', '256']);

// Get array of associate items
$associatedItem = $associator->getAssociations(['3', '8', '16'], 5, 5);
