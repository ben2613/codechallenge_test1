<?php

namespace Kpwong\Netpaytest1\Service;

use Kpwong\Netpaytest1\Database\NodeDao;
use Kpwong\Netpaytest1\Database\PDODatabaseConnection;
use Kpwong\Netpaytest1\Entity\Node;

class NodeQuerier
{
    private NodeDao $dao;
    private $pathCache = array(); // array id => Path
    public function __construct(private PDODatabaseConnection $pdoConn)
    {
        $this->dao = new NodeDao($pdoConn);
    }
    public function query($keyword)
    {
        return array_map(array($this, 'retrieveFullPath'), $this->dao->findByName($keyword));
    }
    private function retrieveFullPath(Node $node)
    {
        $parentPath = '';
        $parentId = $node->getParentId();
        if (array_key_exists($parentId, $this->pathCache)) {
            $parentPath = $this->pathCache[$parentId];
        } else if ($node->getParentId() !== -1) {
            $parentPath = $this->retrieveFullPath($this->dao->findById($parentId)) . '\\';
            $this->pathCache[$parentId] = $parentPath;
        }
        return $parentPath . $node->getName();
    }
}
