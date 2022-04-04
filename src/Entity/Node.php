<?php

namespace Kpwong\Netpaytest1\Entity;

class Node extends AbstractEntity
{
    public static int $ROOT_ID = -1;
    function __construct(?int $id, private ?int $parentId, private bool $isDirectory, private string $name)
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
    public function setName($name)
    {
        $this->name = $name;
    }
}
