<?php

namespace Nuxia\ValuelistBundle\Form\Type;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValuelistChoiceType extends AbstractValuelistChoiceType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        parent::setDefaultOptions($resolver);
        $resolver->setDefaults(array(
            'property' => 'code',
            'choices' => function (Options $options) {
                return $this->valuelistManager->findStmtByCriteria(
                    $options['locale'], $options['category'], $options['parent'], $options['criteria'], $options['property']
                );
            },
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nuxia_valuelist_valuelist_choice';
    }
}
