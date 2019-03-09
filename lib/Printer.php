<?php declare(strict_types=1);

namespace PHPAstVisualizer;

use PhpParser\Node;

use phpDocumentor\GraphViz\Graph;
use phpDocumentor\GraphViz\Edge as GraphEdge;
use phpDocumentor\GraphViz\Node as GraphNode;
class Printer {
    private $nodeMap;
    private $options;
    private $graph;
    
    public function __construct() {
        $this->nodeMap = new \SplObjectStorage;
    }

    public function print(array $ast, Options $options = null): Graph {
        $this->options = $options ?? new Options('ast');
        $this->graph = Graph::create($this->options->getName());
        $start = new GraphNode('start', 'start');
        $this->options->node($start);
        $this->graph->setNode($start);
        $this->parseArray($start, $ast, '');
        return $this->graph;
    }

    public function printNode(Node $node, Options $options = null): Graph {
        $this->options = $options ?? new Options('ast');
        $this->graph = Graph::create($this->options->getName());
        $this->parseNode($node);
        return $graph;
    }

    private function parseArray(GraphNode $parent, array $nodes, string $name) {
        foreach ($nodes as $node) {
            if (!$node instanceof Node) {
                continue;
            }
            $child = $this->parseNode($node);
            $this->createEdge($parent, $child, $name);
            $parent = $child;
            $name = 'next';
        }
    }

    private function parseNode(Node $node): GraphNode {
        if (!isset($this->nodeMap[$node])) {
            $this->nodeMap[$node] = new GraphNode(
                'node_' . count($this->nodeMap),
                $this->exportNode($node)
            );
            $this->options->node($this->nodeMap[$node]);
            $this->graph->setNode($this->nodeMap[$node]);
            foreach ($node->getSubNodeNames() as $name) {
                $subNode = $node->$name;
                if (is_object($subNode) && $subNode instanceof Node) {
                    $this->createEdge($this->nodeMap[$node], $this->parseNode($subNode), $name);
                } elseif (is_array($subNode)) {
                    $this->parseArray($this->nodeMap[$node], $subNode, $name);
                }
            }
        }
        return $this->nodeMap[$node];
    }

    private function createEdge(GraphNode $from, GraphNode $to, string $label) {
        $edge = new GraphEdge($from, $to);
        $this->options->edge($edge);
        $edge->setlabel($label);
        $this->graph->link($edge);
    }

    protected function exportNode(Node $node): string {
        if ($node instanceof Node\Scalar\EncapsedStringPart) {
            return $this->printNodeValue($node, '"' . $node->value . '"');
        } elseif ($node instanceof Node\Scalar\String_) {
            return $this->printNodeValue($node, '"' . $node->value . '"');
        }
        $result = [];
        foreach ($node->getSubNodeNames() as $name) {
            if (is_bool($node->$name)) {
                $result[] = "{$name}: " . ($node->$name ? 'true' : 'false');
            } elseif (is_scalar($node->$name)) {
                $result[] = "{$name}: {$node->$name}";
            }
        }
        return $this->printNodeValue($node, ...$result);
    }

    private function printNodeValue(Node $node, string ... $parts): string {
        return $node->getType() . '\\l' . implode('\\l', $parts);
    }

}