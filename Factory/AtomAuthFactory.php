<?php

namespace Atom\AuthenticationBundle\Factory;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\DefinitionDecorator;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;

/**
 * AtomAuthFactory
 */
class AtomAuthFactory implements SecurityFactoryInterface
{
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $providerId = 'atom.'.$id;
        $container
            ->setDefinition($providerId, new DefinitionDecorator('atom.authentication.provider'))
            ->replaceArgument(0, new Reference($userProvider))
            ->replaceArgument(2, $config['lifetime'])
        ;

        $listenerId = 'atom.authentication.listener.atom.'.$id;
        $listener = $container->setDefinition($listenerId, new DefinitionDecorator('atom.authentication.listener'));

        return array($providerId, $listenerId, $defaultEntryPoint);
    }

    protected function getListenerId()
    {
        return 'atom.security.listener';
    }

    public function getPosition()
    {
        return 'pre_auth';
    }

    public function getKey()
    {
        return 'atom';
    }

    public function addConfiguration(NodeDefinition $node)
    {
        $node
            ->children()
            ->scalarNode('lifetime')->defaultValue(300)
            ->end();
    }

}
