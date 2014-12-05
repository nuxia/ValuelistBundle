<?php

namespace Nuxia\ValuelistBundle\Form\Handler;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class AbstractFormHandler
{
    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var \Symfony\Component\Form\FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var \Symfony\Component\Form\FormInterface
     */
    protected $form;

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @return null|\Symfony\Component\HttpFoundation\Request
     */
    protected function getCurrentRequest()
    {
        return $this->requestStack->getCurrentRequest();
    }

    /**
     * @param FormFactoryInterface $formFactory
     */
    public function setFormFactory(FormFactoryInterface $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @throws \RuntimeException
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm()
    {
        if ($this->form === null) {
            throw new \RuntimeException('You must call process method before using getForm()');
        }
        return $this->form;
    }
}
