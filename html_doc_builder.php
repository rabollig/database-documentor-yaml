<?php
require "vendor/autoload.php";

include "header.html";

use Symfony\Component\Yaml\Yaml;
$schema = Yaml::parseFile('schema.yaml')[0];

foreach ($schema['tables'] as $tableId => $entry ) {
    foreach ($entry as $tableName => $table){
        echo "<a name='" . htmlentities($tableName) ."'></a>" . PHP_EOL;
        echo "<h1>" . htmlentities($tableName) . "</h1>" . PHP_EOL;
        echo "<p>" . htmlentities($table['description']) . "</p>" . PHP_EOL;

        echo "<table>";
        echo "<tr>";
        echo "<td class='heading'>Name</td>";
        echo "<td class='heading'>Type</td>";
        echo "<td class='heading'>Nullable</td>";
        echo "<td class='heading'>Comment</td>";
        echo "</tr>" . PHP_EOL;


        foreach ($table['columns'] as $thisColumn) {
            foreach ($thisColumn as $columnName => $columnAttributes) {
             //   var_dump($columnAttributes);
               // die();
                echo "<tr>";
                echo "<td>" . htmlentities($columnName) . "</td>";
                echo "<td>" . htmlentities($columnAttributes['columnType'] ?? '') . "</td>";
                echo "<td>" . htmlentities($columnAttributes['nullable'] ?? '') . "</td>";
                echo "<td>" . htmlentities($columnAttributes['comment'] ?? '') . "</td>";
                echo "</tr>" . PHP_EOL;
            }
        }
        echo "</table>" . PHP_EOL;
    }
    echo "<br /><br />" . PHP_EOL;
}

include "footer.html";