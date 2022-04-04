<?php

namespace Kpwong\Netpaytest1\Service;

use Kpwong\Netpaytest1\Database\PDODatabaseConnection;
use Kpwong\Netpaytest1\Parser\XMLNodeParser;
use Kpwong\Netpaytest1\Database\NodeTreeSaver;

class WriteFileIntoDatabase
{
    public function __construct(private PDODatabaseConnection $pdoDbConn)
    {
    }
    public function writeFileIntoDatabase($filepath)
    {
        $parser = new XMLNodeParser();
        $nodetree = $parser->parse($filepath);
        $nodetreeSaver = new NodeTreeSaver($this->pdoDbConn);
        $nodetreeSaver->save($nodetree);
    }
}
