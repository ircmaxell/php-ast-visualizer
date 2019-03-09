<?php declare(strict_types=1);

namespace PHPAstVisualizer;

use phpDocumentor\GraphViz\Graph as OrigGraph;

class Graph extends OrigGraph {

    protected $rankings = [];

    public static function create($name = 'G', $directional = true)
    {
        $graph = new self();
        $graph
            ->setName($name)
            ->setType($directional ? 'digraph' : 'graph');
        return $graph;
    }

    public function addRanking(string $type, array $nodes) {
        $rank = '{ rank=' . $type;
        foreach ($nodes as $node) {
            $rank .= ' ' . $node->getName();
        }
        $rank .= ' }';
        $this->rankings[] = $rank;
    }

    public function __toString(): string
    {
        $elements = array_merge(
            $this->graphs,
            $this->attributes,
            $this->edges,
            $this->nodes,
            $this->rankings
        );
        $attributes = [];
        foreach ($elements as $value) {
            $attributes[] = (string) $value;
        }
        $attributes = implode(PHP_EOL, $attributes);
        $strict = ($this->isStrict() ? 'strict ' : '');
        return <<<DOT
{$strict}{$this->getType()} "{$this->getName()}" {
${attributes}
}
DOT;
    }
}