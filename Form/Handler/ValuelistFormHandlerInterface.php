<?php

namespace Nuxia\ValuelistBundle\Form\Handler;

use Nuxia\ValuelistBundle\Entity\Valuelist;

interface ValuelistFormHandlerInterface
{
    /**
     * @param Valuelist $valuelist
     * @param array $options
     *
     * @return bool
     */
    public function process(Valuelist $valuelist = null, array $options = array());

    /*
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getForm();
}
