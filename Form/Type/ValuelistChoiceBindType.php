<?php
namespace Nuxia\ValuelistBundle\Form\Type;

use Nuxia\ValuelistBundle\Manager\AdminValuelistManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ValuelistChoiceBindType extends AbstractType
{
    /**
     * @var AdminValuelistManager
     */
    protected $valueListManager;

    /**
     * @param AdminValuelistManager $valueListManager
     */
    public function __construct(AdminValuelistManager $valueListManager)
    {
        $this->valueListManager = $valueListManager;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'bind-data' => false
        ));

        $resolver->setAllowedTypes(array(
            'bind-data' => array('bool', 'callable')
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        foreach ($view->vars['choices'] as $choice) {
            if (false !== $options['bind-data']) {
                $choice->bind = $options['bind-data'](
                    $this->valueListManager->getValue(
                        $options['locale'], $options['category'], $choice->data
                    )
                );
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'nuxia_valuelist_valuelist_choice';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'nuxia_valuelist_valuelist_bind_choice';
    }
}
