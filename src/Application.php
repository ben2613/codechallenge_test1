<?php

namespace Kpwong\Netpaytest1;

use Kpwong\Netpaytest1\Database\NodeDao;
use Kpwong\Netpaytest1\Database\PDODatabaseConnection;
use Kpwong\Netpaytest1\Service\NodeQuerier;
use Kpwong\Netpaytest1\Service\WriteFileIntoDatabase;

class Application
{
    private $dbconfig;
    private $pdoDbConn;
    public function __construct()
    {
        $this->dbconfig = require __DIR__ . '/../config/database.php';
        $this->pdoDbConn = PDODatabaseConnection::makeNewWithMySQLDetails(
            $this->dbconfig['host'],
            $this->dbconfig['port'],
            $this->dbconfig['database'],
            $this->dbconfig['user'],
            $this->dbconfig['password'],
        );
    }
    public function cleanNodeTable()
    {
        $this->pdoDbConn->getDatabaseHandle()->exec("DELETE FROM node;");
    }
    public function task2($filepath)
    {
        $filePath = $filepath ?? __DIR__ . '/../resources/files.xml';
        $writer = new WriteFileIntoDatabase($this->pdoDbConn);
        $writer->writeFileIntoDatabase($filePath);
    }
    public function echoQueryNodes()
    {
        $keyword = $_GET['keyword'];
        $nq = new NodeQuerier($this->pdoDbConn);
        header('Content-Type: application/json');
        echo json_encode($nq->query($keyword));
    }
}
