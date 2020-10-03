<?php
namespace CodeMade\LiquidBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class LiquidExtension extends Extension
{

    /**
     * Loads the wui pdo configuration.
     *
     * @param array $configs An array of configuration settings
     * @param ContainerBuilder $container
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
        $loader->load('liquid.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $definition = $container->getDefinition('liquid');
        $definition->setPublic(true);
        $definition->setAutoconfigured(true);
        $definition->setAutowired(true);
        $definition->replaceArgument(1, $config);



    }


}
