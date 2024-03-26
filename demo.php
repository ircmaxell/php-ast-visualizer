<?php
require __DIR__ . '/vendor/autoload.php';

use PhpParser\ParserFactory;

$parser = (new ParserFactory)->createForHostVersion();

try {
    $ast = $parser->parse(file_get_contents(__FILE__));
} catch (Error $error) {
    echo "Parse error: {$error->getMessage()}\n";
    return;
}

$printer = new PHPAstVisualizer\Printer;

$graph = $printer->print($ast);

echo (string) $graph;
$graph->export('png', 'demo.png');

function foo(string $a, int $b): void {
    echo $a;
    echo $b;
    echo 3;
}