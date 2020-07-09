<?php
use Symfony\Component\Yaml\Yaml;

require_once "vendor/autoload.php";
require_once "config.php";

$tables = $database->prepare("SHOW TABLE STATUS");
$tables->execute();

$tables= $tables->fetchAll();
foreach ($tables as $table) {
    $outputTable = [];
//    $outputTable['name']  = $table['Name'];
    $outputTable['rows']  = (int)$table['Rows'];
    $outputTable['bytes'] = (int)$table['Rows'] * $table['Avg_row_length'];

    $columns = $database->prepare("
        DESCRIBE {$table['Name']} -- :tablename
    ");
//    $columns->bindValue('tablename', $tableName);
    $columns->execute();
    $columns = $columns->fetchAll();
    $tableColumns = [];
    foreach ($columns as $column) {
        $thisColumn = [];
        $thisColumn['type'] = $column ['Type'];
        $thisColumn['nullable'] = $column['Null'];

        $tableColumns[$column['Field']] = $thisColumn;
    }

    $outputTable['columns'] = $tableColumns;
    $outputTables[$table['Name']] = $outputTable;
break;
}

echo Yaml::dump($outputTables);

PHP_EOL;
PHP_EOL;
PHP_EOL;

$object = new stdClass();
$object->smurf = 'brainy';
$object->muppet = 'kermit';

$data = ['alpha', 'bravo' => (array)$object , 'charlie' => ['one','two', 'three' , ['Bugs', 'Daffy']]];

echo Yaml::dump($data);
