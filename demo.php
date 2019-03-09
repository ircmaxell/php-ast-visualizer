<?php
require __DIR__ . '/vendor/autoload.php';

use PhpParser\ParserFactory;

$parser = (new ParserFactory)->create(ParserFactory::PREFER_PHP7);

try {
    $ast = $parser->parse(file_get_contents(__FILE__));
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$printer = new PHPAstVisualizer\Printer;

$graph = $printer->print($ast);

$graph->export('png', 'test.png');