<?php
//Skeleteal form of the methods that would used to input and retrieve data from my table.
namespace Src\Entity;


class User {

    private int $sequentialId;
    private string $userUuid;
    private ?string $firstName = null;
    private ?string $lastName = null;
    private ?string $email = null;

    private string $phoneNumber;

    private string $password;

    private $dateTime;
    public function setUserSequentialId(int $id): self{
        $this->sequentialId = $id;
        return $this;
    }

    public function getUserSequentialId(): int{
        return $this->sequentialId;
    }


    public function setUserUuid(string $userId): self{
        $this->userUuid = $userId;
        return $this;
    }

    public function getUserUuid(): string{
        return $this->userUuid;
    }

    public function  setFirstName(string $fName): self{

        $this->firstName = $fName;
        return $this;
    }

    public function getFirstName(): string{
        return $this->firstName;
    }

    public function setLastName(string $lName): self{
        $this->lastName = $lName;
        return $this;
    }

    public function getLastName(): string{
        return $this->lastName;
    }

    public function setEmail(string $email): self{
        $this->email = $email;
        return $this;
    }

    public function getEmail(): string{
        return $this->email;
    }

    public function setPhoneNumber(string $phone): self{
        $this->phoneNumber = $phone ;
        return $this;
    }

    public function getPhoneNumber(): string{
        return $this->phoneNumber;
    }

    public function setPassword(string $pass): self{
        $this->password = $pass;
        return $this;
    }

    public function getPassword(): string{
        return $this->password;
    }

    public function setCreatedDate(string $date ): self{
        $this->dateTime = $date;
        return $this;
    }

    public function getCreatedDate(): string{
        return $this->dateTime;
    }


}