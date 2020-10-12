<?php
use Symfony\Component\Yaml\Yaml;
use Nette\Neon\Neon;

require_once "vendor/autoload.php";
require_once "config.php";
require_once "database.inc.php";

$tables = $database->prepare("SHOW TABLE STATUS");
$tables->execute();

$outputTables = [];
$tables= $tables->fetchAll();
foreach ($tables as $table) {
    $outputTable = [];
    $outputTable['rows']  = (int)$table['Rows'];
    $outputTable['bytes'] = (int)$table['Rows'] * $table['Avg_row_length'];

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

        $tableColumns[$column['Field']] = $thisColumn;
    }

    $outputTable['columns'] = (array)$tableColumns;
    $outputTables[$table['Name']] = (array)$outputTable;
}

$output = new stdClass;
$output->tables = $outputTables;

echo Spyc::YAMLDump($output);
