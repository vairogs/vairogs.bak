<?php declare(strict_types = 1);

namespace Vairogs\Component\Utils\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Vairogs\Component\Utils\Vairogs;
use function dump;

final class VairogsConfiguration
{
    /**
     * @var string
     */
    private string $alias;

    public function __construct(string $alias)
    {
        $this->alias = $alias;
    }

    /**
     * @param ArrayNodeDefinition $node
     * @param string $base
     *
     * @return TreeBuilder
     */
    public function getConfiguration(ArrayNodeDefinition $node, string $base = Vairogs::ALIAS): TreeBuilder
    {
        $treeBuilder = new TreeBuilder($base);
        $tree = $treeBuilder->getRootNode();

        $tree->children()
            ->append($node);

        return $treeBuilder;
    }
}
