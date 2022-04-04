<?php

namespace Kpwong\Netpaytest1\Database;

class NodeDao extends AbstractDao
{
    public function getEntityClass()
    {
        return 'Kpwong\Netpaytest1\Entity\Node';
    }
    public function findByName($name)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE name LIKE :name;";
        $stmt = $this->dbh->prepare($query);
        $param = '%' . $name . '%';
        $stmt->bindParam(':name', $param, \PDO::PARAM_STR);
        if ($stmt->execute()) {
            $result = [];
            while ($row = $stmt->fetch()) {
                array_push($result, $this->mapToEntity($row));
            }
            return $result;
        }
        return [];
    }

    // out of test1 scope
    public function findByPath($path)
    {
    }

    // out of test1 scope
    protected function _findByParentIdAndName($parentId, $name)
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE parentId = :parentId AND name = :name;";
        $stmt = $this->dbh->prepare($query);
        $stmt->bindParam(':parentId', $parentId, \PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, \PDO::PARAM_STR);
        // expect single node
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                return $this->mapToEntity($row);
            }
        }
        return null;
    }
}
