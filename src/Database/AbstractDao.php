<?php

namespace Kpwong\Netpaytest1\Database;

use Kpwong\Netpaytest1\Entity\AbstractEntity;

abstract class AbstractDao
{
    private PDODatabaseConnection $db;
    protected \PDO $dbh;
    protected \ReflectionClass $reflection;

    public function __construct(PDODatabaseConnection $db)
    {
        $this->db = $db;
        $this->dbh = $this->db->getDatabaseHandle();
        $this->reflection = new \ReflectionClass($this->getEntityClass());
    }
    public function findAll()
    {
        $query = "SELECT * FROM " . $this->getTableName() . ";";
        $stmt = $this->dbh->prepare($query);
        if ($stmt->execute()) {
            $result = [];
            while ($row = $stmt->fetch()) {
                array_push($result, $this->mapToEntity($row));
            }
            return $result;
        }
        return [];
    }

    public function findById($id)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE id = :id;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                return $this->mapToEntity($row);
            }
        }
        return null;
    }

    public function insert(AbstractEntity $entity)
    {
        $query = "INSERT INTO " . $this->getTableName() . " (";
        $fields = $this->getFields();
        $fields = array_filter($fields, function ($f) {
            return $f !== 'id';
        });

        // Add fields name in query
        $query = $query . implode(', ', $fields) . ") VALUES (";
        // Add param ? in query
        $param = implode(', ', array_fill(0, sizeof($fields), '?'));
        $query =  $query . $param . ");";

        $r = new \ReflectionObject($entity);
        $values = [];
        for ($i = 0; $i < sizeof($fields); $i++) {
            if ($fields[$i] != "id") {
                $p = $r->getProperty($fields[$i]);
                $p->setAccessible(true);
                array_push($values, $p->getValue($entity));
            }
        }

        $this->dbh->prepare($query)->execute($values);

        $entity->setId($this->dbh->lastInsertId());
    }

    public function update(AbstractEntity $entity)
    {
        $query = "UPDATE " . $this->getTableName() . " SET ";
        $fields = $this->getFields();
        // Add fields name = ? in query
        $assigns = implode(', ', array_map(function ($f) {
            return $f . ' = ?';
        }, $fields));
        $query = $query . $assigns . " WHERE  id  = ?;";

        $r = new \ReflectionObject($entity);
        $values = [];
        for ($i = 0; $i < sizeof($fields); $i++) {
            if ($fields[$i] != "id") {
                $p = $r->getProperty($fields[$i]);
                $p->setAccessible(true);
                array_push($values, $p->getValue($entity));
            }
        }
        array_push($values, $entity->getId());

        $this->dbh->prepare($query)->execute($values);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->getTableName() . " WHERE id = ?;";
        $values = [$id];
        $this->dbh->prepare($query)->execute($values);
    }

    // Fully Qualified Class Name
    abstract protected function getEntityClass();
    protected function getTableName()
    {
        return strtolower($this->reflection->getShortName());
    }

    protected function getFields()
    {
        $properties = $this->reflection->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);
        $fields = [];
        foreach ($properties as $prop) {
            array_push($fields, $prop->getName());
        }
        return $fields;
    }

    protected function mapToEntity($row)
    {
        $fields = $this->getFields();
        $properties = [];
        foreach ($fields as $field) {
            $properties[$field] = $row[$field];
        }
        return $this->reflection->newInstanceArgs($properties);
    }
}
