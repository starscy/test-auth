<?php

include $_SERVER['DOCUMENT_ROOT'] . '/exceptions/EmailExistsException.php';
include $_SERVER['DOCUMENT_ROOT'] . '/exceptions/UsernameExistsException.php';
include $_SERVER['DOCUMENT_ROOT'] . '/exceptions/PhoneExistsException.php';

class UserProvider
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function registerUser(string $name, string $phone, string $email,string $password): bool
    {
        $isExistedStatement = $this->pdo->prepare('SELECT email FROM users WHERE email =:email LIMIT 1');
        $isExistedStatement->execute([
            'email' => $email
        ]);
        if ($isExistedStatement->fetch()) {
            throw new EmailExistsException("Пользователь с таким email уже существует");
        }

        $isExistedStatement = $this->pdo->prepare('SELECT phone FROM users WHERE phone =:phone LIMIT 1');
        $isExistedStatement->execute([
            'phone' => $phone
        ]);
        if ($isExistedStatement->fetch()) {
            throw new PhoneExistsException("Пользователь с таким телефоном уже существует");
        }

        $isExistedStatement = $this->pdo->prepare('SELECT name FROM users WHERE name =:name LIMIT 1');
        $isExistedStatement->execute([
            'name' => $name
        ]);
        if ($isExistedStatement->fetch()) {
            throw new UsernameExistsException("Пользователь с таким именем уже существует");
        }

        $statement = $this->pdo->prepare(
            'INSERT INTO users (name, phone, email, password) VALUES (:name, :phone, :email, :password)'
        );

         $statement->execute([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => (string)($password),
        ]);

         if(!$statement) {
             throw new Exception('Ошибка при регистрации');
         }

        return $this->pdo->lastInsertId();
    }

    public function updateDataUser(int $id, string $name, string $phone, string $email,string $password): bool
    {
        $statement = $this->pdo->prepare(
            'UPDATE users SET name=:name, phone=:phone, email=:email, password=:password WHERE id=:id LIMIT 1'
        );

        $statement->execute([
            'id' => $id,
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'password' => (string)($password),
        ]);

        return $this->pdo->lastInsertId();
    }

    public function getByUsernameAndPassword(string $login, string $password)
    {
        $statement = $this->pdo->prepare(
            'SELECT id, name, phone, email, password FROM users WHERE email=:email OR phone = :phone AND password = :password LIMIT 1'
        );
        $statement->execute([
            'email' => $login,
            'phone' => (string)$login,
            'password' => (string)($password)
        ]);
        return $statement->fetch(PDO::FETCH_ASSOC);
    }
}