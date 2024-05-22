# PHP Ast Visualizer

This library will take an AST generated from [Nikita's PHP-Parser](https://github.com/nikic/PHP-Parser) and generate a nice pretty graph representation.

This isn't really that useful, but maybe you'll find a use for it.

Check out [`demo.php`](demo.php) for examples.

![A demo graphic](demo.png)

## Installation
First, make sure you have the `dot` command installed. You can do this by installing [graphviz](https://graphviz.org/download/). Then,
```bash
composer require ircmaxell/php-ast-visualizer
```
