<?php declare(strict_types=1);

namespace PHPAstVisualizer;

use PhpParser\NodeVisitorAbstract;
use PhpParser\Node;
use phpDocumentor\GraphViz\Edge as GraphEdge;
use phpDocumentor\GraphViz\Graph;
use phpDocumentor\GraphViz\Node as GraphNode;

class Visitor extends NodeVisitorAbstract {

    private $graph;
    private $nodeStore;
    private $nodeMap = [];
    private $options;

    public function start(Graph $graph, Options $options) {
        $this->graph = $graph;
        $this->options = $options;
        $this->nodeMap = new \SplObjectStorage;
    }

    public function enterNode(Node $node) {
        $this->nodesStack[] = $node;
        return null;
    }

    public function leaveNode(Node $node) {
        $prior = $node;
        while (end($this->nodesStack) !== $node) {
            $tmp = array_pop($this->nodesStack);
            $this->createEdge($node, $tmp);
        }
        return null;
    }

    public function afterTraverse(array $nodes) {
        $start = new GraphNode('start', 'start');
        $this->graph->setNode($start);
        foreach ($nodes as $node) {
            $next = $this->createNode($node);
            $edge = new GraphEdge($start, $next);
            $start = $next;
            $this->options->edge($edge);
            $edge->setstyle('dashed');
            $edge->setarrowhead('empty');
            $this->graph->link($edge);
        }
        return null;
    }

    private function createNode(Node $node): GraphNode {
        if (!isset($this->nodeMap[$node])) {
            $this->nodeMap[$node] = new GraphNode(
                'node_' . count($this->nodeMap),
                $this->printNode($node)
            );
            $this->options->node($this->nodeMap[$node]);
            $this->graph->setNode($this->nodeMap[$node]);
        }
        return $this->nodeMap[$node];
    }

    private function createEdge(Node $from, Node $to) {
        $edge = new GraphEdge($this->createNode($from), $this->createNode($to));
        $this->options->edge($edge);
        $this->graph->link($edge);
    }

    protected function printNode(Node $node): string {
        if ($node instanceof Node\Scalar\EncapsedStringPart) {
            return $this->printNodeValue($node, '"' . $node->value . '"');
        } elseif ($node instanceof Node\Scalar\String_) {
            return $this->printNodeValue($node, '"' . $node->value . '"');
        }
        $result = [];
        foreach ($node->getSubNodeNames() as $name) {
            if (is_scalar($node->$name)) {
                $result[] = "{$name}: {$node->$name}";
            }
        }
        return $this->printNodeValue($node, ...$result);
    }

    private function printNodeValue(Node $node, string ... $parts): string {
        return $node->getType() . '\\l' . implode('\\l', $parts);
    }
}