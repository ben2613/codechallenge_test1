<?php

namespace Kpwong\Netpaytest1\Database;

class NodeDao extends AbstractDao
{
    public function getEntityClass()
    {
        return 'Kpwong\Netpaytest1\Entity\Node';
    }
}
