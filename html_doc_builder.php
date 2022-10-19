<?php
declare(strict_types = 1);
require "vendor/autoload.php";

use Jenssegers\Blade\Blade;
use Symfony\Component\Yaml\Yaml;

try {
    $config = parse_ini_file('config.ini', true);
} catch (Exception $e) {
    die("Unable to parse config file. Make sure you copied config.ini.example to config.ini and filled it in.");
}

$schema = Yaml::parseFile('schema.yaml');

$blade = new Blade('templates', 'templates/cache');

$output = $blade->render(
    'template',
    [
        'title' => $config['document']['title'],
        'schema' => $schema
    ]
);

file_put_contents($config['files']['document_file'], $output);
