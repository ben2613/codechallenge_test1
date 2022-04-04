<?php

namespace Kpwong\Netpaytest1\Database;

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
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE " . $this->getIdField() . " = :id;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':id', $id);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                return $this->mapToEntity($row);
            }
        }
        return null;
    }

    public function insert($entity)
    {
        $query = "INSERT INTO " . $this->getTableName() . " (";
        $fields = $this->getFields();
        for ($i = 0; $i < sizeof($fields); $i++) {
            if ($fields[$i] != $this->getIdField()) {
                $query = $query . $fields[$i];
                if ($i < sizeof($fields) - 1) {
                    $query = $query . ", ";
                }
            }
        }

        $query = $query . ") VALUES (";
        for ($i = 0; $i < sizeof($fields); $i++) {
            if ($fields[$i] != $this->getIdField()) {
                $query = $query . "?";
                if ($i < sizeof($fields) - 1) {
                    $query = $query . ", ";
                }
            }
        }
        $query = $query . ");";

        $values = [];
        for ($i = 0; $i < sizeof($fields); $i++) {
            if ($fields[$i] != $this->getIdField()) {
                array_push($values, $entity->{$fields[$i]});
            }
        }

        $this->dbh->prepare($query)->execute($values);
    }

    public function update($entity)
    {
        $query = "UPDATE " . $this->getTableName() . " SET ";
        $fields = $this->getFields();
        for ($i = 0; $i < sizeof($fields); $i++) {
            if ($fields[$i] != $this->getIdField()) {
                $query = $query . $fields[$i] . " = ?";
                if ($i < sizeof($fields) - 1) {
                    $query = $query . ", ";
                }
            }
        }
        $query = $query . " WHERE " . $this->getIdField() . " = ?" . ";";

        $values = [];
        for ($i = 0; $i < sizeof($fields); $i++) {
            if ($fields[$i] != $this->getIdField()) {
                array_push($values, $entity->{$fields[$i]});
            }
        }
        array_push($values, $entity->{$this->getIdField()});

        $this->dbh->prepare($query)->execute($values);
    }

    public function delete($id)
    {
        $query = "DELETE FROM " . $this->getTableName() . " WHERE " . $this->getIdField() . " = ?;";
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
        $properties = $this->reflection->getProperties(\ReflectionProperty::IS_PRIVATE);
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

    protected function getIdField()
    {
        return "id";
    }
}
