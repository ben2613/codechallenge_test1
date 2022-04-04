<?php

namespace Kpwong\Netpaytest1\Entity;

class Node extends AbstractEntity
{
    function __construct(int|null $id, private int $parentId, private bool $isDirectory, private string $name)
    {
        parent::__construct($id);
    }
    public function getParentId()
    {
        return $this->parentId;
    }
    public function getIsDirectory()
    {
        return $this->isDirectory;
    }
    public function getName()
    {
        return $this->name;
    }
}
