<?php

namespace Src\Entity;

class foodItem {
    private string $foodUuid;

    private string $foodName;

    private int $foodPrice;

    private bool $foodAvailabilty;

    private $dateTime;


public function setFoodUuid(string $foodId): self{
    $this->foodUuid = $foodId;
    return $this;
}


public function getFoodUuid(){
    return $this->foodUuid;
}


public function setFoodName( string $foodName): self{
    $this->foodName = $foodName;
    return $this;
}

public function getFoodName(){
    return $this->foodName;
}

public function setFoodPrice( string|int $foodPrice): self{
    $this->foodPrice = $foodPrice;
    return $this;
}

public function getFoodPrice(){
    return $this->foodPrice;
}

public function setFoodAvailability( bool $foodAvailability): self{
    $this->foodAvailabilty = $foodAvailability;
    return $this;
}

public function getFoodAvailability(){
    return $this->foodAvailabilty;
}


public function setCreatedDate(string $date ): self{
    $this->dateTime = $date;
    return $this;
}

public function getCreatedDate(): string{
    return $this->dateTime;
}
}