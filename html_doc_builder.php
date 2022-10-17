<?php
declare(strict_types = 1);
require "vendor/autoload.php";

use Jenssegers\Blade\Blade;
use Symfony\Component\Yaml\Yaml;

$schema = Yaml::parseFile('schema.yaml');

$blade = new Blade('templates', 'templates/cache');

$output = $blade->render(
    'template',
    [
        'schema' => $schema
    ]
);

echo $output;
