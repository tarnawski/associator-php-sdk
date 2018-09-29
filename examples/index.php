<?php

use Associator\Client;
use Associator\Associator;

require_once '../vendor/autoload.php';

$client = new Client();
$associator = new Associator($client);
//$associator->setApiKey('bf357212-41d4-41de-a0a5-01372c939583');

//$result = $associator->createApplication('test', '07fb7006-773a-4c4e-82dd-6f9dc05cbaeb');
$result = $associator->saveTransaction([1,2]);
//$result = $associator->getAssociations([2], 1, 1);
//$result = $associator->importTransactions([[7,14],[9],[3,8]]);

var_dump($result);exit;