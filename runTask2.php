<?php
require __DIR__ . '/vendor/autoload.php';

use Kpwong\Netpaytest1\Application;

echo "Are you sure you want to run this script? Previous data will be erased." . PHP_EOL;
echo "Type 'yes' to continue: ";

$handle = fopen("php://stdin", "r");
$line = fgets($handle);
if (trim($line) != 'yes') {
    echo "ABORTING!\n";
    exit;
}
fclose($handle);
$fileXmlPath = __DIR__ . "/resources/files.xml";
$app = new Application();
$app->cleanNodeTable();
$app->task2($fileXmlPath);
echo "File are read into db. You can find the data with adminer at http://localhost:8081" . PHP_EOL;
