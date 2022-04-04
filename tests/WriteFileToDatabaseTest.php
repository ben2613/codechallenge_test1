<?php

use Kpwong\Netpaytest1\Service\WriteFileIntoDatabase;
use Kpwong\Netpaytest1\Database\PDODatabaseConnection;
use Kpwong\Netpaytest1\Database\NodeDao;
use Kpwong\Netpaytest1\Entity\Node;
use PHPUnit\Framework\TestCase;

final class WriteFileToDatabaseTest extends TestCase
{
    private NodeDao | null $nodeDao;
    private static PDODatabaseConnection $dbc;
    public function testParseFile(): void
    {
        $filePath = __DIR__ . '/../resources/files.xml';
        $writer = new WriteFileIntoDatabase(self::$dbc);
        $writer->writeFileIntoDatabase($filePath);
        $this->assertCount(19, $this->nodeDao->findAll());
        // check the folders are parsed correctly
        $this->assertEquals(Node::$ROOT_NAME, $this->nodeDao->findById(Node::$ROOT_ID)->getName());
        $c = $this->nodeDao->findByName('C:')[0];
        $this->assertEquals(Node::$ROOT_ID, $c->getParentId());
        $documents = $this->nodeDao->findByName('Documents')[0];
        $this->assertEquals($c->getId(), $documents->getParentId());
        $this->assertEquals(1, $documents->getIsDirectory());
        $images = $this->nodeDao->findByName('Images')[0];
        $image1 = $this->nodeDao->findByName('Image1.jpg')[0];
        $this->assertEquals($images->getId(), $image1->getParentId());
        $this->assertEquals(0, $image1->getIsDirectory());
    }

    public static function setUpBeforeClass(): void
    {
        // setup a tmp sqlite db
        self::$dbc = PDODatabaseConnection::makeNew('sqlite::memory:', null, null);
        // self::$dbc = PDODatabaseConnection::makeNew('sqlite::memory:', null, null);
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
        $this->nodeDao = new NodeDao(self::$dbc);
    }
    protected function tearDown(): void
    {
        $this->nodeDao = null;
        // self::$dbc->getDatabaseHandle()->exec("DELETE FROM node;");
    }
}
