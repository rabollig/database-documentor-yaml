<?php
declare(strict_types = 1);

require_once "vendor/autoload.php";
require_once "config.php";
require_once "database.inc.php";

if (!isset($database)) {
    die('I need access to the database to scan it, but cannot stat');
}

// Get the basic information on the tables
$tables = $database->prepare("SHOW TABLE STATUS");
$tables->execute();

// Get information about foreign keys
$foreignKeysQuery = $database->prepare("
    SELECT TABLE_NAME, COLUMN_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
      FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
     WHERE CONSTRAINT_SCHEMA = :databaseName
       AND CONSTRAINT_NAME != 'PRIMARY'");
$foreignKeysQuery->bindValue('databaseName', DB_NAME);
$foreignKeysQuery->execute();
$foreignKeysQueryResults = $foreignKeysQuery->fetchAll();


$keyConstraints = [];
foreach ($foreignKeysQueryResults as $constraint) {
    $keyConstraints[
        $constraint['TABLE_NAME'] . '.' . $constraint['COLUMN_NAME']
    ] = $constraint['REFERENCED_TABLE_NAME'] . '.' . $constraint['REFERENCED_COLUMN_NAME'];
}

$outputTables = [];
$tables= $tables->fetchAll();
foreach ($tables as $table) {
    $outputTable = [];
    $outputTable['rows']  = (int)$table['Rows'];
    $outputTable['bytes'] = (int)$table['Data_length'];
    $outputTable['comment'] = $table['Comment'];

    // Binding doesn't work for DESCRIBE for some reason, however, it's
    // unlikely someone named a table in the database to be a SQL Injection,
    // and if a bad actor can do that, you have bigger problems than a
    // lack of documentation. Still feels icky, though.
    $columns = $database->prepare("
        DESCRIBE `{$table['Name']}` -- :tablename
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

        // Add foreign key constraints, if any
        if (!empty($keyConstraints[$table['Name'] . '.' . $column['Field']])) {
            $thisColumn['foreignKeyConstraint'] = $keyConstraints[$table['Name'] . '.' . $column['Field']];
        }
        $tableColumns[$column['Field']] = $thisColumn;
    }

    $outputTable['columns'] = (array)$tableColumns;
    $outputTables[$table['Name']] = (array)$outputTable;

}

$output = [];
$output['tables'] = $outputTables;

echo yaml_emit($output);

