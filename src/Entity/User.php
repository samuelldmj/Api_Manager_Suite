<?php
//Skeleteal form of the methods that would used to input data into our table.
namespace Src\Entity;


class User {

    private string $userUuid;
    private string $firstName;
    private string $lastName;
    private string $email;

    private string $phoneNumber;

    private string $password;

    private $dateTime;

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