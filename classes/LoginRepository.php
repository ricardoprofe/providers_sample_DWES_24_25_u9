<?php
declare(strict_types=1);
require_once __DIR__ . '/Login.php';
require_once __DIR__ . '/DBConnection.php';
require_once __DIR__ . '/IDbAccess.php';

class LoginRepository implements IDbAccess
{

    public static function getAll(): ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM login");
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Login');
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id) : ?Login
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM login WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Login');
            $stmt->execute(['id' => $id]);
            $login = $stmt->fetch();
            if($login) {
                return $login;
            }
        }
        return null;
    }


    public static function insert($object) : false | string
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO login (id, email, password) VALUES (:id, :email, :password)");
            $stmt->execute([
                'id' => null,
                'email' => $object->getEmail(),
                'password' => $object->getPassword()
            ]);
            return $conn->lastInsertId();
        }
        return false;
    }

    public static function delete($object) : int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM login WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function update($object) : int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE login SET email = :email, password = :password, role = :role  WHERE id = :id");
            $stmt->execute([
                'id' => $object->getId(),
                'email' => $object->getEmail(),
                'password' => $object->getPassword(),
                'role' => $object->getRole()
            ]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function searchByEmail($object): int {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT email FROM login WHERE email=:email");
            $stmt->execute(['email'=>$object->getEmail()]);
            return $stmt->rowCount();
        }
        return 0;
    }

    public static function checkCredential($clearPassword, $object): bool|int {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM login WHERE email=:email");
            $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, 'Login');
            $stmt->execute(['email'=>$object->getEmail()]);
            $login = $stmt->fetch();
            if($login && password_verify($clearPassword, $login->getPassword())){
                //Returns the id login if successful
                return $login->getId();
            } else {
                return false;
            }
        }
        return false;
    }
}