<?php

namespace Nuxia\ValuelistBundle\Form\Type;

use Nuxia\ValuelistBundle\Helper\ValuelistParser;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValuelistTreeChoiceType extends AbstractValuelistChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'choices' => function (Options $options) {
                return ValuelistParser::valuelistToTreeChoices($this->valuelistManager->findByCriteria(
                    $options['locale'], $options['category'], $options['parent'], $options['criteria']
                ));
            },
        ));
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nuxia_valuelist_tree_valuelist_choice';
    }
}
