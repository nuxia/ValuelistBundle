<?php

namespace Nuxia\ValuelistBundle\Form\Type;

use Nuxia\ValuelistBundle\Manager\ValuelistManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class AbstractValuelistChoiceType extends AbstractType
{
    /**
     * @var ValuelistManagerInterface
     */
    protected $valuelistManager;

    /**
     * @param ValuelistManagerInterface $valuelistManager
     */
    public function setValuelistManager(ValuelistManagerInterface $valuelistManager)
    {
        $this->valuelistManager = $valuelistManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setRequired(array('category'));
        $resolver->setDefaults(array('locale' => 'default', 'parent' => 'null', 'criteria' => array()));
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }
}
