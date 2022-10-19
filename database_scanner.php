<?php
declare(strict_types = 1);
use Symfony\Component\Yaml\Yaml;

require_once "vendor/autoload.php";

// Get configuration
try {
    $config = parse_ini_file('config.ini', true);
} catch (Exception $e) {
    die("Unable to parse config file. Make sure you copied config.ini.example to config.ini and filled it in.");
}

// Connect to the database
try {
    $database = new PDO(
        "mysql:host="
        . $config['database']['database_host']
        . ";dbname=" . $config['database']['database_name'],
        $config['database']['database_user'],
        $config['database']['database_password']
    );
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Unable to connect to the database: " . $e->getMessage());
}

if (!isset($database)) {
    die('I need access to the database to scan it, but cannot stat');
}

// Import existing schema.yaml file, if any, so we can reuse the description fields
if (file_exists($config['files']['schema_file'])) {
    try {
        $previousSchema = Yaml::parseFile($config['files']['schema_file']);
    } catch (Exception $e) {
        die(
            "I ran into problems parsing your existing schema file, "
            . $config['files']['schema_file'] . ".  I'm stopping so I don't break anything."
            . PHP_EOL . $e->getMessage()
        );
    }
} else {
    $previousSchema = [];
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
$foreignKeysQuery->bindValue('databaseName', $config['database']['database_name']);
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
    $outputTable['bytes'] = (int)$table['Avg_row_length'] * (int)$table['Rows'];
    $outputTable['comment'] = $table['Comment'];
    $outputTable['type'] = $tableType[$table['Name']];
    $outputTable['description'] = $previousSchema['tables'][$table['Name']]['description'] ?? '';

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
        $thisColumn['description']
            = $previousSchema['tables'][$table['Name']]['columns'][$column['Field']]['description'] ?? '';

        // Add foreign key constraints, if any
        if (!empty($keyConstraints[$table['Name'] . '.' . $column['Field']])) {
            $thisColumn['foreignKeyConstraint'] = $keyConstraints[$table['Name'] . '.' . $column['Field']];
        }

        // Sample column values
        $samples = [];
        if ($config['samples']['enabled'] && $column['Key'] != 'UNI' && $table['Rows'] > 0) {
            $sampleLimit = 0;
            if (!empty($column['Key'])
                && $table['Rows'] < $config['samples']['max_row_length_indexed']
            ) {
                $sampleLimit = $config['samples']['top_values_indexed'];
            }
            if (empty($column['Key'])
                && $table['Rows'] < $config['samples']['max_row_length_unindexed']
            ) {
                $sampleLimit = $config['samples']['top_values_unindexed'];
            }

            if ($sampleLimit) {
                $sampleQuery = $database->prepare("
                    SELECT `{$column['Field']}` AS value, COUNT(*) AS qty
                    FROM `{$table['Name']}`
                    GROUP BY `{$column['Field']}`
                    ORDER BY qty DESC LIMIT {$sampleLimit}
                ");
                $sampleQuery->execute();
                $sampleQueryResults = $sampleQuery->fetchAll();

                foreach ($sampleQueryResults as $sampleQueryResult) {
                    // Skip if it's just a number
                    if (!is_numeric($sampleQueryResult['value'])) {
                        $samples[] = [
                            'value' => $sampleQueryResult['value'],
                            'qty' => $sampleQueryResult['qty'],
                            'percentage' => round($sampleQueryResult['qty'] / $table['Rows'] *100, 2)
                        ];
                    }
                }

                $thisColumn['samples'] = $samples;
            }
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

$output = [];
$output['tables'] = $outputTables;

file_put_contents($config['files']['schema_file'], yaml_emit($output));
