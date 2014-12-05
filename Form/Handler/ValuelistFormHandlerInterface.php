<?php

namespace Nuxia\ValuelistBundle\Form\Handler;

use Nuxia\Component\Form\Handler\FormHandlerInterface;
use Nuxia\ValuelistBundle\Entity\Valuelist;

interface ValuelistFormHandlerInterface extends FormHandlerInterface
{
    /**
     * @param Valuelist $valuelist
     * @param array $options
     *
     * @return bool
     */
    public function process(Valuelist $valuelist = null, array $options = array());
}
