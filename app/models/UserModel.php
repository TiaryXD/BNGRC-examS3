<?php
namespace app\models;

class UserModel {
    private int $id;
    private string $email;
    private string $password;
    private string $created_at;

    public function __construct($id, $mail) {
        $this->setId($id);
        $this->setMail($mail);
    }

    public function setId(int $id): void { $this->id = $id; }
    public function getId(): int { return $this->id; }

    public function setMail(string $email): void { $this->email = $email; }
    public function getMail(): string { return $this->email; }

    public function setPassword(string $password): void { $this->password = $password; }
    public function getPassword(): string { return $this->password; }

    public function setCreatedAt(string $date): void { $this->created_at = $date; }
    public function getCreatedAt(): string { return $this->created_at; }
}