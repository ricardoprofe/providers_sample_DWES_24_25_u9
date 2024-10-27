<?php
declare(strict_types=1);

class Provider
{
    private int $id;
    private string $name;
    private string $email;
    private string $cif;

    public function __construct()
    {
        $this->id = 0;
        $this->name = '';
        $this->email = '';
        $this->cif = '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getCif(): string
    {
        return $this->cif;
    }

    public function setCif(string $cif): void
    {
        $this->cif = $cif;
    }

    public function validate(): array {
        $errors = [];

        if (empty($this->name)) {
            $errors['name'] = "* Name is required";
        } elseif (strlen($this->name) < 4) {
            $errors['name'] = "* Name must be at least 4 characters long";
        }

        if(empty($this->email)) {
            $errors['email'] = "* Email is required";
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "* Invalid email format";
        }

        if(empty($this->cif)) {
            $errors['cif'] = "* CIF is required";
        } elseif (!preg_match("/^[A-Z a-z]{1}[0-9]{8}$/", $this->cif)) {
            $errors['cif'] = "* Invalid CIF format";
        }

        return $errors;
    }

    public function __toString(): string
    {
        return "Provider: " . $this->name . " " . $this->email . " " . $this->cif;
    }

    public function toArray(): array
    {
        return array(
            'id' => $this->getId(),
            'name' => $this->getName(),
            'email' => $this->getEmail(),
            'cif' => $this->getCif()
        );
    }


}