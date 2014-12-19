<?php

namespace Nuxia\ValuelistBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SetConfigurationAdminPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->getParameter('nuxia_valuelist.admin.enabled') === true) {
            $categories = $container->getParameter('nuxia_valuelist.admin.categories');
            $valuelistManagerDefinition = $container->getDefinition('nuxia_valuelist.manager');
            $valuelistManagerDefinition->setClass('Nuxia\ValuelistBundle\Manager\AdminValuelistManager');
            $valuelistManagerDefinition->addMethodCall('setCategories', array($categories));
            $valuelistExtensionDefinition = $container->getDefinition('nuxia_valuelist.twig.extension');
            $valuelistExtensionDefinition->setClass('Nuxia\ValuelistBundle\Twig\AdminValuelistExtension');
        }
    }
}
