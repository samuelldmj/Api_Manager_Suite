<?php 
declare(strict_types=1);

namespace Tests\Entity;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Src\Entity\Item as ItemEntity;

final class ItemTest extends TestCase{

private ItemEntity $itemEntity;

protected function setUp(): void{
    parent::setUp();
    $this->itemEntity = new ItemEntity();
}

public function testSequetialId():void {
    $expectedId = 45;
    $this->itemEntity->setSequentialId($expectedId);
    $this->assertSame($expectedId, $this->itemEntity->getSequentialId());
}


public function testItemPrice(){
    $expectedPrice = 40.00;
    $this->itemEntity->setItemPrice($expectedPrice);
    $this->assertSame($expectedPrice, $this->itemEntity->getItemPrice());
}

public function testItemAvailabilty(){
    $expectedResult = (bool) "true";
    $this->itemEntity->setItemAvailabilty($expectedResult);
    $this->assertSame($expectedResult, $this->itemEntity->getItemAvailabilty());
}


    public function testUnserialize():void{

        $expectedUuid = Uuid::uuid4()->toString();
        $expectedItemData = [
            "id" => 7,
            "item_uuid" => $expectedUuid,
            "item_name" => 'Laptop',
            "item_price" => 100.9,
            "item_availability" => true
        ];

        $this->itemEntity->unserialize($expectedItemData);
        $this->assertSame($expectedItemData['id'], $this->itemEntity->getSequentialId());
        $this->assertSame($expectedItemData['item_uuid'], $this->itemEntity->getItemUuid());
        $this->assertSame($expectedItemData['item_name'], $this->itemEntity->getItemName());
        $this->assertSame($expectedItemData['item_price'], $this->itemEntity->getItemPrice());
        $this->assertSame($expectedItemData['item_availability'], $this->itemEntity->getItemAvailabilty());
    }



}