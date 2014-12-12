<?php

namespace Nuxia\ValuelistBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class NuxiaValuelistExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services/default.yml');
        $loader->load('services/form.yml');
        $this->loadAdmin($config, $container, $loader);
        unset($config['fixtures']);
    }

    /**
     * @param array            $config
     * @param ContainerBuilder $container
     * @param YamlFileLoader   $loader
     */
    private function loadAdmin(array &$config, ContainerBuilder $container, YamlFileLoader $loader)
    {
        $enabled = $config['admin']['enabled'];
        if ($enabled === true) {
            $loader->load('services/admin.yml');
            $categories = $config['admin']['categories'];
            $container->setParameter('nuxia_valuelist.admin.categories', $categories);
            $container->setParameter(
                'nuxia_valuelist.admin.category_requirements', implode('|', array_keys($categories))
            );
        }
        $container->setParameter('nuxia_valuelist.admin.enabled', $enabled);
        unset($config['admin']);
    }
}
