<?php

use Associator\Associator;

class AssociatorTest extends \PHPUnit\Framework\TestCase
{
    private $apiKey;
    private $transactions;

    public function setUp()
    {
        $this->apiKey = '6090b2a5-c5fe-421b-b1f9-fa67dca2d829';
        $this->transactions = ['3', '8', '16'];
    }

    public function testSaveTransaction()
    {
        $client = $this->getMockBuilder(\Associator\Client::class)
            ->setMethods(array('request'))
            ->getMock();

        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/transactions', 'POST', [
                'api_key' => $this->apiKey,
                'transaction' => $this->transactions
            ])->willReturn('{"status": "Success"}');

        $associator = new Associator($client, $this->apiKey);

        $this->assertEquals(true, $associator->saveTransaction($this->transactions));
    }

    public function testSaveTransactionWhenClientThrowException()
    {
        $client = $this->getMockBuilder(\Associator\Client::class)
            ->setMethods(array('request'))
            ->getMock();

        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/transactions', 'POST', [
                'api_key' => $this->apiKey,
                'transaction' => $this->transactions
            ])->will($this->throwException(new \Associator\ClientException()));

        $associator = new Associator($client, $this->apiKey);

        $this->assertEquals(false, $associator->saveTransaction($this->transactions));
    }

    public function testGetAssociations()
    {
        $client = $this->getMockBuilder(\Associator\Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/associations?api_key=6090b2a5-c5fe-421b-b1f9-fa67dca2d829&samples=%5B5%2C18%5D')
            ->willReturn('{"status":"Success","associations":[[3],[8,16]]}');

        $associator = new Associator($client, $this->apiKey);

        $this->assertEquals([[3],[8,16]], $associator->getAssociations([5,18]));
    }

    public function testGetAssociationsWithSupportAndConfidence()
    {
        $client = $this->getMockBuilder(\Associator\Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/associations?api_key=6090b2a5-c5fe-421b-b1f9-fa67dca2d829&samples=%5B5%2C18%5D&support=10&confidence=20')
            ->willReturn('{"status":"Success","associations":[[3],[8,16]]}');

        $associator = new Associator($client, $this->apiKey);

        $this->assertEquals([[3],[8,16]], $associator->getAssociations([5,18], 10, 20));
    }

    public function testGetAssociationsWhenClientThrowException()
    {
        $client = $this->getMockBuilder(\Associator\Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/associations?api_key=6090b2a5-c5fe-421b-b1f9-fa67dca2d829&samples=%5B5%2C18%5D')
            ->will($this->throwException(new \Associator\ClientException()));

        $associator = new Associator($client, $this->apiKey);

        $this->assertEquals([], $associator->getAssociations([5,18]));
    }

    public function testGetAssociationsWhenApiReturnWrongResponse()
    {
        $client = $this->getMockBuilder(\Associator\Client::class)
            ->setMethods(array('request'))
            ->getMock();
        $client->expects($this->once())
            ->method('request')
            ->with('api.associator.eu/v1/associations?api_key=6090b2a5-c5fe-421b-b1f9-fa67dca2d829&samples=%5B5%2C18%5D')
            ->willReturn('{"status":"Error"}');

        $associator = new Associator($client, $this->apiKey);

        $this->assertEquals([], $associator->getAssociations([5,18]));
    }
}