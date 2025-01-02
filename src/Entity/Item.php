<?php
namespace Src\Entity;

class Item implements Entitiable {
    private int $sequentialId;
    private string $itemName;

    private ?string $itemUuid = null;

    private float $itemPrice;

    private bool $itemAvailabilty;


    public function setSequentialId(int $itemId){
        $this->sequentialId = $itemId;
    }

    public function getSequentialId(): int{
        return $this->sequentialId;
    }

    public function setItemUuid(string $uuid): void{
         $this->itemUuid = $uuid;
    }

    public function getItemUuid(): ?string{
        return $this->itemUuid;
    }

    public function setItemPrice(float $price): void{
            $this->itemPrice = $price;
    }

    public function getItemPrice(): float{
       return $this->itemPrice;
}

public function setItemName(string $name): void{
    $this->itemName = $name;
}

public function getItemName(): string{
    return $this->itemName;
}

public function setItemAvailabilty(bool $available): void{
    $this->itemAvailabilty = $available;
}

public function getItemAvailabilty(): bool{
   return  $this->itemAvailabilty;
}


public function unserialize(?array $data):self {
    
    if(!empty($data['id'])){
        $this->setSequentialId($data['id']);
    }

    if(!empty($data['item_name'])){
        $this->setItemName($data['item_name']);
    }

    if(!empty($data['item_price'])){
        $this->setItemPrice($data['item_price']);
    }

    if(!empty($data['item_availability'])){
        $this->setItemAvailabilty($data['item_availability']);
    }

    if(!empty($data['item_uuid'])){
        $this->setItemUuid($data['item_uuid']);
    }

    return $this;

}

}

