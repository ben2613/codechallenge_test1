<?php

namespace Kpwong\Netpaytest1\Parser;

use Kpwong\Netpaytest1\Entity\Node;

class NodeTree
{
    public Node $node; // which id and parentId are not confirmed before inserting into DB
    public ?array $children; //NodeTree[]
}

abstract class AbstractNodeParser
{
    protected $nodeTree; // {node, children: [{node, children}...]}
    abstract public function parse(string $filepath): NodeTree|null;
}
