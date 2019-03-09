<?php declare(strict_types=1);

namespace PHPAstVisualizer;

use phpDocumentor\GraphViz\Edge as GraphEdge;
use phpDocumentor\GraphViz\Graph;
use phpDocumentor\GraphViz\Node as GraphNode;

class Options {
    private $graphOptions;
    private $nodeOptions;
    private $edgeOptions;
    private $name;


    public function __construct(string $name, array $graphOptions = null, array $nodeOptions = null, array $edgeOptions = null) {
        $this->name = $name;
        $this->graphOptions = $graphOptions ?? [];
        $this->nodeOptions = $nodeOptions ?? ['shape' => 'rect'];
        $this->edgeOptions = $edgeOptions ?? [];
    }

    public function getName(): string {
        return $this->name;
    }

    public function graph(Graph $graph) {
        foreach ($this->graphOptions as $name => $value) {
            $graph->{'set' . $name}($value);
        }
    }

    public function node(GraphNode $node) {
        foreach ($this->nodeOptions as $name => $value) {
            $node->{'set' . $name}($value);
        }
    }

    public function edge(GraphEdge $edge) {
        foreach ($this->edgeOptions as $name => $value) {
            $edge->{'set' . $name}($value);
        }
    }
}