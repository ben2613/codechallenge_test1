<?php

namespace Kpwong\Netpaytest1\Parser;

use Kpwong\Netpaytest1\Entity\Node;

class XMLNodeParser extends AbstractNodeParser
{
    public function parse(string $filepath): NodeTree|null
    {
        $root = new NodeTree;
        $root->node = new Node(Node::$ROOT_ID, Node::$ROOT_ID, true, Node::$ROOT_NAME);
        $xml = null;
        try {
            $rootxml = new \SimpleXMLElement($filepath, 0, true);
            $this->parseChildrenIntoTree($root, $rootxml->children());
        } catch (\Throwable $th) {
            return null;
        }

        return $root;
    }
    private function parseChildrenIntoTree(&$nodetree, $children)
    {
        $childrenTrees = array();
        foreach ($children as $child) {
            $tagName = $child->getName();
            $childTree = new NodeTree;
            $isDirectory = ($tagName === 'drive' || $tagName === 'folder');
            $childNode = new Node(null, null, $isDirectory, $child['name']);
            $childTree->node = $childNode;
            $this->parseChildrenIntoTree($childTree, $child->children());
            array_push($childrenTrees, $childTree);
        }
        $nodetree->children = $childrenTrees;
    }
}
