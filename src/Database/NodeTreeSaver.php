<?php

namespace Kpwong\Netpaytest1\Database;

use Kpwong\Netpaytest1\Parser\NodeTree;
use Kpwong\Netpaytest1\Database\NodeDao;
use Kpwong\Netpaytest1\Database\PDODatabaseConnection;

class NodeTreeSaver
{
    private NodeDao $dao;
    public function __construct(private PDODatabaseConnection $conn)
    {
        $this->dao = new NodeDao($conn);
    }
    public function save(NodeTree $tree, $parentId = null): void
    {
        $tree->node->setParentId($parentId);
        $this->dao->insert($tree->node);
        foreach ($tree->children as $subtree) {
            $this->save($subtree, $tree->node->getId());
        }
    }
}
