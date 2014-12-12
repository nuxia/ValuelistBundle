<?php

namespace Nuxia\ValuelistBundle;

use Nuxia\ValuelistBundle\DependencyInjection\Compiler\SetConfigurationAdminPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NuxiaValuelistBundle extends Bundle
{
    /**
     * @param ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new SetConfigurationAdminPass());
    }
}
