<?php
namespace Src\Entity;

class Item implements Entitiable {
    private int $sequentialId;
    private string $itemName;

    private ?string $itemUuid = null;

    private float $itemPrice;

    private bool $itemAvailabilty;


    public function setSequentialId(int $itemId): self{
        $this->sequentialId = $itemId;
        return $this;
    }

    public function getSequentialId(): int{
        return $this->sequentialId;
    }

    public function setItemUuid(string $uuid): self{
         $this->itemUuid = $uuid;
         return $this;
    }

    public function getItemUuid(): ?string{
        return $this->itemUuid;
    }

    public function setItemPrice(float $price): self{
            $this->itemPrice = $price;
            return $this;
    }

    public function getItemPrice(): float{
       return $this->itemPrice;
}

public function setItemName(string $name): self{
    $this->itemName = $name;
    return $this;
}

public function getItemName(): string{
    return $this->itemName;
}

public function setItemAvailabilty(bool $available): self{
    $this->itemAvailabilty = $available;
    return $this;
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

