<?php

use Kpwong\Netpaytest1\Database\NodeDao;
use Kpwong\Netpaytest1\Database\PDODatabaseConnection;
use Kpwong\Netpaytest1\Entity\Node;
use PHPUnit\Framework\TestCase;

final class NodeDaoTest extends TestCase
{
    private NodeDao | null $nodeDao;

    public function testFindAll(): void
    {
        $this->assertCount(4, $this->nodeDao->findAll());
    }

    public function testFindById(): void
    {
        $obj = $this->nodeDao->findById(3);
        $this->assertEquals('file.txt', $obj->getName());
        $obj = $this->nodeDao->findById(1000);
        $this->assertNull($obj);
    }

    public function testFindByName(): void
    {
        $ret = $this->nodeDao->findByName('file');
        $this->assertCount(2, $ret);
    }

    public function testInsert(): void
    {
        $c = $this->nodeDao->findByName('C:')[0];
        $node = new Node(null, $c->getId(), false, 'newfile.txt'); // add file at C:
        $this->nodeDao->insert($node);
        $this->assertEquals(5, $node->getId());
    }

    private static PDODatabaseConnection $dbc;
    public static function setUpBeforeClass(): void
    {
        // setup a tmp sqlite db
        self::$dbc = PDODatabaseConnection::makeNew('sqlite::memory:', null, null);
        self::$dbc->getDatabaseHandle()->exec(
            "CREATE TABLE node (
                id INT PRIMARY KEY,
                parentId INT,
                isDirectory INT,
                name TEXT
                )"
        );
    }
    protected function setUp(): void
    {
        self::$dbc->getDatabaseHandle()->exec("INSERT INTO node (id, parentId, isDirectory, name) VALUES (1, -1, 1, 'C:');");
        self::$dbc->getDatabaseHandle()->exec("INSERT INTO node (id, parentId, isDirectory, name) VALUES (2, 1, 1, 'folder');");
        self::$dbc->getDatabaseHandle()->exec("INSERT INTO node (id, parentId, isDirectory, name) VALUES (3, 2, 1, 'file.txt');");
        self::$dbc->getDatabaseHandle()->exec("INSERT INTO node (id, parentId, isDirectory, name) VALUES (4, 2, 1, 'file2.txt');");
        $this->nodeDao = new NodeDao(self::$dbc);
    }
    protected function tearDown(): void
    {
        self::$dbc->getDatabaseHandle()->exec("DELETE FROM node;");
        $this->nodeDao = null;
    }
}
