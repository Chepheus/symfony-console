<?php

namespace App\ChainCommandBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class CompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        /**
         * Use tags for commands connection
         */

        $taggedParentServices = $container->findTaggedServiceIds('chain_command.parent');
        $taggedChildServices = $container->findTaggedServiceIds('chain_command.child');

        foreach ($taggedParentServices as $parentId => $pTags) {
            $childIds = \array_intersect($parentId::getChildCommands(), \array_keys($taggedChildServices));
            foreach ($childIds as $childId) {
                $container->getDefinition($childId)->addMethodCall('setParent', [new Reference($parentId)]);
                $container->getDefinition($parentId)->addMethodCall('addToChain', [new Reference($childId)]);
            }
        }
    }
}