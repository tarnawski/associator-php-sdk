<?php

use Associator\Associator;
use Associator\Client;
use Associator\Exception\AssociatorException;

class AssociatorTest extends \PHPUnit\Framework\TestCase
{
    public function testGetAssociations()
    {
        $response = ["status" => "Success", "associations" => [[3],[8,16]]];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/associations?api_key=6090b2a5-c5fe-421b-b1f9-fa67dca2d829&samples=%5B5%2C18%5D')
            ->willReturn(json_encode($response));

        $associator = new Associator($client);
        $associator->setApiKey('6090b2a5-c5fe-421b-b1f9-fa67dca2d829');

        $this->assertEquals($response, $associator->getAssociations([5,18]));
    }

    public function testGetAssociationsWithoutApiKey()
    {
        $client = $this->getMockBuilder(Client::class)->getMock();
        $associator = new Associator($client);

        $this->expectException(AssociatorException::class);
        $associator->getAssociations([5,18]);
    }

    public function testSaveTransaction()
    {
        $response = ["status" => "Success", "message" => "Transaction saved."];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/transactions', 'POST', [
                'api_key' => '6090b2a5-c5fe-421b-b1f9-fa67dca2d829',
                'items' => ['3','8','16']
            ])->willReturn(json_encode($response));

        $associator = new Associator($client);
        $associator->setApiKey('6090b2a5-c5fe-421b-b1f9-fa67dca2d829');

        $this->assertEquals($response, $associator->saveTransaction(['3','8','16']));
    }

    public function testSaveTransactionWithoutApiKey()
    {
        $client = $this->getMockBuilder(Client::class)->getMock();

        $associator = new Associator($client);

        $this->expectException(AssociatorException::class);
        $associator->saveTransaction(['3','8','16']);
    }

    public function testImportTransactions()
    {
        $response = ["status" => "Success", "message" => "Transaction saved."];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/import', 'POST', [
                'api_key' => '6090b2a5-c5fe-421b-b1f9-fa67dca2d829',
                'data' => "NywxNAo5CjMsOA=="
            ])->willReturn(json_encode($response));

        $associator = new Associator($client);
        $associator->setApiKey('6090b2a5-c5fe-421b-b1f9-fa67dca2d829');

        $this->assertEquals($response, $associator->importTransactions([[7,14],[9],[3,8]]));
    }

    public function testImportTransactionsWithoutApiKey()
    {
        $client = $this->getMockBuilder(Client::class)->getMock();

        $associator = new Associator($client);

        $this->expectException(AssociatorException::class);
        $associator->importTransactions([[7,14],[9],[3,8]]);
    }
}