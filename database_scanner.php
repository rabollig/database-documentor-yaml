<?php
declare(strict_types = 1);

require_once "vendor/autoload.php";
require_once "config.php";
require_once "database.inc.php";

if (!isset($database)) {
    die('I need access to the database to scan it, but cannot stat');
}

$tables = $database->prepare("SHOW TABLE STATUS");
$tables->execute();

$outputTables = [];
$tables= $tables->fetchAll();
foreach ($tables as $table) {
    $outputTable = [];
    $outputTable['rows']  = (int)$table['Rows'];
    $outputTable['bytes'] = (int)$table['Rows'] * $table['Avg_row_length'];
    $outputTable['comment'] = $table['Comment'];

    $columns = $database->prepare("
        DESCRIBE {$table['Name']} -- :tablename
    ");
    $columns->execute();
    $columns = $columns->fetchAll();
    $tableColumns = [];
    foreach ($columns as $column) {
        $thisColumn = [];
        $thisColumn['type'] = $column ['Type'];
        $thisColumn['nullable'] = $column['Null'];
        $thisColumn['key'] = $column['Key'];
        $thisColumn['default'] = $column['Default'];
        $thisColumn['extra'] = $column['Extra'];


        $tableColumns[$column['Field']] = $thisColumn;
    }

    $outputTable['columns'] = (array)$tableColumns;
    $outputTables[$table['Name']] = (array)$outputTable;
}

$output = new stdClass;
$output->tables = $outputTables;

echo yaml_emit($output);