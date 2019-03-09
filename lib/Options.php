<?php declare(strict_types=1);

namespace PHPAstVisualizer;

use phpDocumentor\GraphViz\Edge as GraphEdge;
use phpDocumentor\GraphViz\Graph;
use phpDocumentor\GraphViz\Node as GraphNode;

class Options {
    private $options = [
        'graph' => [],
        'node' => ['shape' => 'rect'],
        'edge' => [],
        'childEdge' => ['style' => 'dashed','arrowhead' => 'empty'],
    ];
    private $name;


    public function __construct(string $name, array $options = []) {
        $this->name = $name;
        $this->graphOptions = array_merge($this->options, $options);
    }

    public function getName(): string {
        return $this->name;
    }

    public function graph(Graph $graph) {
        foreach ($this->options['graph'] as $name => $value) {
            $graph->{'set' . $name}($value);
        }
    }

    public function node(GraphNode $node) {
        foreach ($this->options['node'] as $name => $value) {
            $node->{'set' . $name}($value);
        }
    }

    public function childEdge(GraphEdge $edge) {
        $this->edge($edge);
        foreach ($this->options['childEdge'] as $name => $value) {
            $edge->{'set' . $name}($value);
        }
    }

    public function edge(GraphEdge $edge) {
        foreach ($this->options['edge'] as $name => $value) {
            $edge->{'set' . $name}($value);
        }
    }
}