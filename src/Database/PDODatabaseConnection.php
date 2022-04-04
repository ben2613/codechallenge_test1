<?php

namespace Kpwong\Netpaytest1\Database;

/**
 * Must be more complicated / we use proper frameworks in real code
 */
class PDODatabaseConnection
{
    private function __construct(
        private string $connectionString,
        private ?string $user,
        private ?string $password
    ) {
    }

    public static function makeNewWithMySQLDetails(
        $host,
        $port,
        $database,
        $user,
        $password
    ) {
        return self::makeNew("mysql:dbname={$database};host={$host};port={$port}", $user, $password);
    }

    public static function makeNew($connStr, $user, $password)
    {
        $obj = new PDODatabaseConnection($connStr, $user, $password);
        $obj->connect();
        return $obj;
    }

    public function connect()
    {
        $this->dbh = new \PDO(
            $this->connectionString,
            $this->user,
            $this->password
        );
    }

    public function getDatabaseHandle(): \PDO
    {
        return $this->dbh;
    }
}
