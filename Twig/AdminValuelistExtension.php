<?php

namespace Nuxia\ValuelistBundle\Twig;

class AdminValuelistExtension extends ValuelistExtension
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $functions = parent::getFunctions();
        $functions['valuelist_action_valid'] = new \Twig_Function_Method($this, 'isActionExistsOnCategory', array('is_safe' => array('html')));

        return $functions;
    }

    /**
     * @param $action
     * @param $category
     * @return mixed
     */
    public function isActionExistsOnCategory($action, $category)
    {
        return $this->valuelistManager->isActionExistsOnCategory($action, $category);
    }
}
