<?php
declare(strict_types = 1);
require_once "vendor/autoload.php";

use Symfony\Component\Yaml\Yaml;

$schema = Yaml::parseFile('schema.yaml')[0];
//var_dump($schema['tables']);
//echo sizeof($schema['tables']);

foreach ($schema['tables'] as $entry) {
    foreach ($entry as $tableName => $tableInfo) {
        echo "CREATE TABLE {$tableName} (" . PHP_EOL;

        $columnSQL = [];


        foreach ($tableInfo['columns'] as $columnArray) {
            foreach ($columnArray as $columnName => $column) {

                if ($column['nullable']) {
                    $nullable = '';
                } else {
                    $nullable = 'NOT NULL';
                }

                if ($tableInfo['primaryKey'] == $columnName) {
                    $primaryKeySQL = "PRIMARY KEY";
                } else {
                    $primaryKeySQL = '';
                }

                // No line breaks... easier to read
                $definition = "{$column['columnType']} {$nullable} {$primaryKeySQL}";


                if (!empty($column['comment'])) {
                    $definition .= " COMMENT '" . addslashes($column['comment']) . "'";
                }

                $columnSQL[] = "  {$columnName} {$definition}";


                // If there's a foreign key
                if (isset($column['foreignKey'])) {
                    $references = $column['foreignKey']['references'];
                    $columnSQL[]
                        = "  FOREIGN KEY ({$columnName}) REFERENCES {$references}";
                }


            }
        }

        echo implode(",\n", $columnSQL);
        echo PHP_EOL;

        echo ")" . PHP_EOL;
        $engine = $tableInfo['engine'] ?? 'Default';
        echo "ENGINE = {$engine}" . PHP_EOL;
        echo "COMMENT '" . addslashes($tableInfo['description']) ."';" . PHP_EOL;
        echo PHP_EOL;


        // Make indexes, if any
        foreach ($tableInfo['indexes'] as $index) {
            echo "CREATE INDEX {$tableName}_{$index} ON {$tableName}($index);". PHP_EOL;
        }
        echo PHP_EOL;
        echo PHP_EOL;

    }
}


echo "-- Produced by Code Knights YAML Schema Document Creator, v0.9.12 " . date("Y-m-d");
echo PHP_EOL;
echo PHP_EOL;
