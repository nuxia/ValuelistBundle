<?php

namespace Nuxia\ValuelistBundle\Twig;

use Nuxia\ValuelistBundle\Manager\AdminValuelistManagerInterface;

class AdminValuelistExtension
{
    /**
     * @var AdminValuelistManagerInterface
     */
    protected $valuelistManager;

    /**
     * @param AdminValuelistManagerInterface $valuelistManager
     */
    public function __construct(AdminValuelistManagerInterface $valuelistManager)
    {
        $this->valuelistManager = $valuelistManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'valuelist_action_valid' => new \Twig_SimpleFunction('isActionExistsOnCategory', array('is_safe' => array('html'))),
        );
    }

    /**
     * @param  string $action
     * @param  string $category
     *
     * @return bool
     */
    public function isActionExistsOnCategory($action, $category)
    {
        return $this->valuelistManager->isActionExistsOnCategory($action, $category);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'admin_valuelist';
    }
}
