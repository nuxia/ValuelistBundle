<?php

namespace Nuxia\ValuelistBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValuelistType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_token_id' => 'valuelist',
                'data_class' => 'Nuxia\ValuelistBundle\Entity\Valuelist',
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', 'text', array(
            'read_only' => !$builder->getData()->isNew(),
            'translation_domain' => 'NuxiaValueList'
        ));

        $builder->add('label', 'text', array('translation_domain' => 'NuxiaValueList'));

        $builder->add('value', 'number', array(
            'invalid_message' => 'valuelist.value.invalid_number',
            'required' => false,
            'translation_domain' => 'NuxiaValueList'
        ));
        $builder->add('submit', 'submit', array('translation_domain' => 'NuxiaValueList'));
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->children['code']->vars['label'] = 'valuelist.code.label';
        $view->children['label']->vars['label'] = 'valuelist.label.label';
        $view->children['value']->vars['label'] = 'valuelist.value.label';
        $view->children['submit']->vars['label'] = 'valuelist.submit.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nuxia_valuelist_valuelist';
    }
}
