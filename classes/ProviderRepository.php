<?php
declare(strict_types=1);
require_once __DIR__ . '/Provider.php';
require_once __DIR__ . '/DBConnection.php';
require_once __DIR__ . '/IDbAccess.php';

class ProviderRepository implements IDbAccess
{

    public static function getAll() : ?array
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("SELECT * FROM providers");
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Provider');
            $stmt->execute();
            return $stmt->fetchAll();
        } else {
            return null;
        }
    }

    public static function select($id): ?Provider
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            // The user input is automatically quoted, so there is no risk of a SQL injection attack.
            $stmt = $conn->prepare("SELECT * FROM providers WHERE id = :id");
            $stmt->setFetchMode(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'Provider');
            $stmt->execute(['id' => $id]);
            $provider = $stmt->fetch();
            if($provider) {
                return $provider;
            }
        }
        return null;
    }


    public static function insert($object): false | string
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("INSERT INTO providers (id, name, email, cif) VALUES (:id, :name, :email, :cif)");
            $stmt->execute([
                'id' => null,
                'name' => $object->getName(),
                'email' => $object->getEmail(),
                'cif' => $object->getCif()
            ]);
            return $conn->lastInsertId();
        }
        return false;
    }

    public static function delete($object) : int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("DELETE FROM providers WHERE id = :id");
            $stmt->execute(['id' => $object->getId()]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }

    public static function update($object): int
    {
        $conn = DBConnection::connectDB();
        if (!is_null($conn)) {
            $stmt = $conn->prepare("UPDATE providers SET name = :name, email = :email, cif = :cif WHERE id = :id");
            $stmt->execute([
                'id' => $object->getId(),
                'name' => $object->getName(),
                'email' => $object->getEmail(),
                'cif' => $object->getCif()
            ]);
            return $stmt->rowCount(); //Return the number of rows affected
        }
        return 0;
    }
}