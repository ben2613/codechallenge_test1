<?php

namespace Kpwong\Netpaytest1\Entity;

class Node extends AbstractEntity
{
    public function __construct(private int $id, private int $parentId, private bool $isDirectory, private string $name)
    {
    }
    public function getId()
    {
        return $this->id;
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
