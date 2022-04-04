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

    public function testInsertWithSpecificId(): void
    {
        $node = new Node(-2, -1, false, 'file-2');
        $this->nodeDao->insert($node);
        $ret = $this->nodeDao->findByName('file-2');
        $this->assertCount(1, $ret);
        $this->assertEquals(-2, $ret[0]->getId());
    }

    public function testUpdate(): void
    {
        $c = $this->nodeDao->findByName('C:')[0];
        $c->setName('D:');
        $this->nodeDao->update($c);
        $this->assertCount(1, $this->nodeDao->findByName('D:'));
    }

    public function testDelete(): void
    {
        $originalCount = count($this->nodeDao->findAll());
        $c = $this->nodeDao->findByName('C:')[0];
        $this->nodeDao->delete($c->getId());
        $this->assertCount($originalCount - 1, $this->nodeDao->findAll());
    }

    private static PDODatabaseConnection $dbc;
    public static function setUpBeforeClass(): void
    {
        // setup a tmp sqlite db
        self::$dbc = PDODatabaseConnection::makeNew('sqlite::memory:', null, null);
        self::$dbc->getDatabaseHandle()->exec(
            "CREATE TABLE node (
                id INTEGER PRIMARY KEY,
                parentId INTEGER,
                isDirectory INTEGER,
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
