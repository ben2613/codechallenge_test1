<?php

namespace Kpwong\Netpaytest1\Entity;

/**
 * May contains some common fields when in real life code e.g. createdDateTime
 * 
 */
abstract class AbstractEntity
{
    function __construct(protected int|null $id)
    {
    }
    public function getId()
    {
        return $this->id;
    }
    public function setId(int $id)
    {
        $this->id = $id;
    }
}
