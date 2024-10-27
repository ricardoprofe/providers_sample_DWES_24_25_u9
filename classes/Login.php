<?php
declare(strict_types=1);

class Login
{
    private int $id;
    private string $email;
    private string $password;
    private string $role;

    public function __construct()
    {
        $this->id = 0;
        $this->email = '';
        $this->password = '';
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function setRole(string $role): void
    {
        $this->role = $role;
    }



    public function validate(): array {
        $errors = [];
        if(empty($this->email)){
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)){
            $errors['email'] = 'Email is not valid';
        }

        if(empty($this->password)){
            $errors['password'] = 'Password is required';
        } elseif (strlen($this->password) < 8){
            $errors['password'] = 'Password must be at least 8 characters';
        }

        return $errors;
    }
}