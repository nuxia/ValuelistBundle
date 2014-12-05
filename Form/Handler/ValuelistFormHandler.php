<?php

namespace Nuxia\ValuelistBundle\Form\Handler;

use Nuxia\ValuelistBundle\Entity\Valuelist;
use Nuxia\ValuelistBundle\Manager\ValuelistManager;

class ValuelistFormHandler extends AbstractFormHandler implements ValuelistFormHandlerInterface
{
    /**
     * @var ValuelistManager
     */
    protected $valueManager;

    /**
     * @var string
     */
    protected $formName;

    /**
     * @param ValuelistManager $valuelistManager
     */
    public function setValuelistManager(ValuelistManager $valuelistManager)
    {
        $this->valuelistManager = $valuelistManager;
    }

    /**
     * @param string $formName
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;
    }

    /**
     * @param null $data
     * @param array $options
     */
    protected function createForm($data = null, array $options = array())
    {
        if ($data !== null) {
            $options['data'] = $data;
        }
        $this->form = $this->formFactory->create($this->formName, $data, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function process(Valuelist $valuelist = null, array $options = array())
    {
        if ($valuelist === null) {
            $valuelist = $this->valuelistManager->create(
                array('category' => $this->getCurrentRequest()->attributes->get('category'))
            );
        }
        $this->createForm($valuelist, $options);
        if ($this->form->handleRequest($this->getCurrentRequest()) && $this->form->isValid()) {
            $this->onSuccess($this->form->getData());
            return true;
        }
        return false;
    }

    /**
     * @param Valuelist $valuelist
     */
    protected function onSuccess(Valuelist $valuelist)
    {
        $this->valuelistManager->persist($valuelist);
    }
}
