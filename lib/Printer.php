<?php declare(strict_types=1);

namespace PHPAstVisualizer;

use PhpParser\NodeTraverser;

use phpDocumentor\GraphViz\Graph;

class Printer {
    private $traverser;
    private $visitor;
    
    public function __construct() {
        $this->traverser = new NodeTraverser;
        $this->visitor = new Visitor;
        $this->traverser->addVisitor($this->visitor);
    }

    public function print(array $ast, Options $options = null): Graph {
        $options = $options ?? new Options('ast');
        $graph = Graph::create($options->getName());
        $this->visitor->start($graph, $options);
        $this->traverser->traverse($ast);
        return $graph;
    }


}