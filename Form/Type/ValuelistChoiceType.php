<?php

namespace Nuxia\ValuelistBundle\Form\Type;

use Nuxia\ValuelistBundle\Manager\ValuelistManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValuelistChoiceType extends AbstractType
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
        $resolver->setDefaults(
            array(
                'locale' => 'default',
                'property' => 'code',
                'parent' => 'null',
                'criteria' => array(),
                'choices' => function (Options $options) {
                        return $this->valuelistManager->findStmtByCriteria(
                            $options['locale'], $options['category'], $options['parent'], $options['criteria'], $options['property']
                        );
                    },
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nuxia_valuelist_valuelist_choice';
    }
}
