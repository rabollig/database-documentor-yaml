<?php
use Symfony\Component\Yaml\Yaml;
use Nette\Neon\Neon;
require_once "vendor/autoload.php";
require_once "config.php";

$tables = $database->prepare("SHOW TABLE STATUS");
$tables->execute();

$outputTables = [];
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

    $outputTable['columns'] = (array)$tableColumns;
    $outputTables[$table['Name']] = (array)$outputTable;
}
/*
var_dump($outputTables);
die();
*/

$output = new stdClass;
$output->tables = $outputTables;

echo Spyc::YAMLDump($output);



/*

PHP_EOL;
PHP_EOL;
PHP_EOL;

$object = new stdClass();
$object->smurf = 'brainy';
$object->muppet = 'kermit';

$data = ['alpha', 'bravo' => (array)$object , 'charlie' => ['one','two', 'three' , ['Bugs', 'Daffy']]];
$data = [];
$data['alpha']='a';
$data['bravo']='b';
$data['charlie']='c';

$data = ['tables'=>['alpha'=>1, 'bravo'=>2, 'charlie'=>[4,5,6]]];
//echo Yaml::dump($data);
//echo Spyc::YAMLDump($data);
echo Neon::encode($data);
*/
