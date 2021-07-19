<?php


namespace App\ChainCommandBundle\DependencyInjection;


use App\ChainCommandBundle\Command\AbstractChildCommand;
use App\ChainCommandBundle\Command\AbstractParentCommand;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class ChainCommandExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        /**
         * Mark command by special tags
         */

        $container->registerForAutoconfiguration(AbstractChildCommand::class)
            ->addTag('chain_command.child');

        $container->registerForAutoconfiguration(AbstractParentCommand::class)
            ->addTag('chain_command.parent');
    }
}