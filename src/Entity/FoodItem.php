<?php

namespace Src\Entity;

class FoodItem extends Item {
    // Additional FoodItem-specific properties
    private ?string $expiryDate = null;
    private ?int $calories = null;

    // Setter for expiry date
    public function setExpiryDate(string $expiryDate): self {
        $this->expiryDate = $expiryDate;
        return $this;
    }

    // Getter for expiry date
    public function getExpiryDate(): ?string {
        return $this->expiryDate;
    }

    // Setter for calories
    public function setCalories(int $calories): self {
        $this->calories = $calories;
        return $this;
    }

    // Getter for calories
    public function getCalories(): ?int {
        return $this->calories;
    }

    // Override the `unserialize` method if needed
    public function unserialize(?array $data): self {
        parent::unserialize($data); // Call the parent unserialize method

        // Handle FoodItem-specific data
        if (!empty($data['expiry_date'])) {
            $this->setExpiryDate($data['expiry_date']);
        }

        if (!empty($data['calories'])) {
            $this->setCalories($data['calories']);
        }

        return $this;
    }
}
