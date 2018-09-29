<?php

use Associator\Associator;
use Associator\Client;
use Associator\Exception\ClientException;

class AssociatorTest extends \PHPUnit\Framework\TestCase
{
    public function testCreateApplication()
    {
        $response = [
            "id" => "57fadadc-ad93-4134-b1dd-0cb05c5d01ea",
            "name" => "test",
            "created_at" => "29-09-2018"
        ];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/applications', 'POST', [
                'name' => 'test',
                'provider' => '38145196-b3e2-4dd1-8953-7e5e0647cb6e'
            ])->willReturn(json_encode($response));

        $associator = new Associator($client);

        $this->assertEquals($response, $associator->createApplication('test', '38145196-b3e2-4dd1-8953-7e5e0647cb6e'));
    }

    public function testCreateApplicationWhenClientThrowException()
    {
        $response = ['status' => 'Error', 'message' => 'Exception message'];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/applications', 'POST', [
                'name' => 'test',
                'provider' => '38145196-b3e2-4dd1-8953-7e5e0647cb6e'
            ])->will($this->throwException(new ClientException('Exception message')));

        $associator = new Associator($client);

        $this->assertEquals($response, $associator->createApplication('test', '38145196-b3e2-4dd1-8953-7e5e0647cb6e'));
    }

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
        $response = ['status' => 'Error', 'message' => 'Api key must be set.'];
        $client = $this->getMockBuilder(Client::class)->getMock();

        $associator = new Associator($client);

        $this->assertEquals($response, $associator->getAssociations([5,18]));
    }

    public function testGetAssociationsWithSupportAndConfidence()
    {
        $response = ["status" => "Success", "associations" => [[3],[8,16]]];
        $client = $this->getMockBuilder(\Associator\Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/associations?api_key=6090b2a5-c5fe-421b-b1f9-fa67dca2d829&samples=%5B5%2C18%5D&support=10&confidence=20')
            ->willReturn(json_encode($response));

        $associator = new Associator($client);
        $associator->setApiKey('6090b2a5-c5fe-421b-b1f9-fa67dca2d829');

        $this->assertEquals($response, $associator->getAssociations([5,18], 10, 20));
    }

    public function testGetAssociationsWithToSmallSupportValue()
    {
        $response = ['status' => 'Error', 'message' => 'Support must be between 0.0 and 1.0'];
        $client = $this->getMockBuilder(\Associator\Client::class)->getMock();


        $associator = new Associator($client);
        $associator->setApiKey('6090b2a5-c5fe-421b-b1f9-fa67dca2d829');

        $this->assertEquals($response, $associator->getAssociations([5,18], 0, 0.5));
    }

    public function testGetAssociationsWhenClientThrowException()
    {
        $response = ['status' => 'Error', 'message' => 'Exception message'];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/associations?api_key=6090b2a5-c5fe-421b-b1f9-fa67dca2d829&samples=%5B5%2C18%5D')
            ->will($this->throwException(new ClientException('Exception message')));

        $associator = new Associator($client);
        $associator->setApiKey('6090b2a5-c5fe-421b-b1f9-fa67dca2d829');

        $this->assertEquals($response, $associator->getAssociations([5,18]));
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
        $response = ['status' => 'Error', 'message' => 'Api key must be set.'];
        $client = $this->getMockBuilder(Client::class)->getMock();

        $associator = new Associator($client);

        $this->assertEquals($response, $associator->saveTransaction(['3','8','16']));
    }

    public function testSaveTransactionWhenClientThrowException()
    {
        $response = ['status' => 'Error', 'message' => 'Exception message'];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/transactions', 'POST', [
                'api_key' => '6090b2a5-c5fe-421b-b1f9-fa67dca2d829',
                'items' => ['3','8','16']])
            ->will($this->throwException(new ClientException('Exception message')));

        $associator = new Associator($client);
        $associator->setApiKey('6090b2a5-c5fe-421b-b1f9-fa67dca2d829');

        $this->assertEquals($response, $associator->saveTransaction(['3','8','16']));
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

    public function testImportTransactionsWhenClientThrowException()
    {
        $response = ['status' => 'Error', 'message' => 'Exception message'];

        $client = $this->getMockBuilder(Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/import', 'POST', [
                'api_key' => '6090b2a5-c5fe-421b-b1f9-fa67dca2d829',
                'data' => "NywxNAo5CjMsOA=="])
            ->will($this->throwException(new ClientException('Exception message')));

        $associator = new Associator($client);
        $associator->setApiKey('6090b2a5-c5fe-421b-b1f9-fa67dca2d829');

        $this->assertEquals($response, $associator->importTransactions([[7,14],[9],[3,8]]));
    }

    public function testImportTransactionsWithoutApiKey()
    {
        $response = ['status' => 'Error', 'message' => 'Api key must be set.'];
        $client = $this->getMockBuilder(Client::class)->getMock();

        $associator = new Associator($client);

        $this->assertEquals($response, $associator->importTransactions([[7,14],[9],[3,8]]));
    }
}