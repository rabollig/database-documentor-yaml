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

// Get full tables (so we can get the table types)
$tableFullStatusQuery = $database->prepare("SHOW FULL TABLES");
$tableFullStatusQuery->execute();
$tableFullStatusResults = $tableFullStatusQuery->fetchAll();

$tableType = [];
foreach ($tableFullStatusResults as $row) {
    $tableType[$row[0]] = $row['Table_type'];
}

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

// Get trigger information
$triggerQuery = $database->prepare("SHOW TRIGGERS;");
$triggerQuery->execute();
$triggerQueryResults = $triggerQuery->fetchAll();
$triggers = [];
foreach ($triggerQueryResults as $trigger) {
    $triggers[$trigger['Table']][$trigger['Event']] = $trigger['Statement'];
}

// Scan tables
$outputTables = [];
$tables= $tables->fetchAll();
foreach ($tables as $table) {
    $outputTable = [];
    $outputTable['rows']  = (int)$table['Rows'];
    $outputTable['bytes'] = (int)$table['Data_length'];
    $outputTable['comment'] = $table['Comment'];
    $outputTable['type'] = $tableType[$table['Name']];

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

    // Add triggers, if any
    if (!empty($triggers[$table['Name']])) {
        $outputTable['triggers'] = $triggers[$table['Name']];
    }

    // If this is a view, get the creation statement (and again with the binding)
    if ($tableType[$table['Name']] === 'VIEW') {
        $viewCreateQuery = $database->prepare("
            SHOW CREATE VIEW `{$table['Name']}`
        ");
        $viewCreateQuery->execute();
        $viewCreateInfo = $viewCreateQuery->fetchAll();
        $outputTable['viewCreateQuery'] = $viewCreateInfo[0]['Create View'];
    }

    $outputTable['columns'] = (array)$tableColumns;
    $outputTables[$table['Name']] = (array)$outputTable;
}

// Add Stored Procedures
$storedProcedureListQuery = $database->prepare("
    SHOW PROCEDURE STATUS WHERE db = :database
");
$storedProcedureListQuery->bindValue("database", DB_NAME);
$storedProcedureListQuery->execute();
$storedProcedureList = $storedProcedureListQuery->fetchAll();

$storedProcedures = [];
foreach ($storedProcedureList as $procedure) {
    $storedProcedureInfoQuery = $database->prepare("
        SHOW CREATE PROCEDURE `{$procedure['Name']}` -- :( 
    ");
    $storedProcedureInfoQuery->execute();
    $storedProcedureInfo = $storedProcedureInfoQuery->fetchAll();
/*

For some reason, this isn't working. When you run the query in MySQL, it works...
mysql> show create procedure increase_all_salaries_by_percentage\G
*************************** 1. row ***************************
           Procedure: increase_all_salaries_by_percentage
            sql_mode: ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION
    Create Procedure: CREATE DEFINER=`root`@`localhost` PROCEDURE `increase_all_salaries_by_percentage`(
    IN percentage DECIMAL(5,2)
)
BEGIN
    UPDATE employees
    SET salary = salary * (1 + percentage/100);
END
character_set_client: utf8mb4
collation_connection: utf8mb4_0900_ai_ci
  Database Collation: utf8mb4_0900_ai_ci
1 row in set (0.00 sec)

mysql>

But when I get it back through PDO, the creation statement isn't there...
array(1) {
  [0]=>
  array(12) {
    ["Procedure"]=>
    string(35) "increase_all_salaries_by_percentage"
    [0]=>
    string(35) "increase_all_salaries_by_percentage"
    ["sql_mode"]=>
    string(117) "ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"
    [1]=>
    string(117) "ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION"
    ["Create Procedure"]=>
    NULL
    [2]=>
    NULL
    ["character_set_client"]=>
    string(7) "utf8mb4"
    [3]=>
    string(7) "utf8mb4"
    ["collation_connection"]=>
    string(18) "utf8mb4_0900_ai_ci"
    [4]=>
    string(18) "utf8mb4_0900_ai_ci"
    ["Database Collation"]=>
    string(18) "utf8mb4_0900_ai_ci"
    [5]=>
    string(18) "utf8mb4_0900_ai_ci"
  }
}


 */



    $storedProcedures[$procedure['Name']] = $storedProcedureInfo['Create Procedure'];
}

$output = [];
$output['tables'] = $outputTables;
$output['storedProcedures'] = $storedProcedures;

echo yaml_emit($output);
