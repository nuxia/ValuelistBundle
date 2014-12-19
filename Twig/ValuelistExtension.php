<?php

namespace Nuxia\ValuelistBundle\Twig;

use Nuxia\ValuelistBundle\Manager\ValuelistManagerInterface;

class ValuelistExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    protected $environnement;

    /**
     * @var string
     */
    protected $locale;

    /**
     * @var ValuelistManagerInterface
     */
    protected $valuelistManager;

    /**
     * @param ValuelistManagerInterface $valuelistManager
     */
    public function __construct(ValuelistManagerInterface $valuelistManager)
    {
        $this->valuelistManager = $valuelistManager;
    }

    /**
     * @param \Twig_Environment $environment
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        //On ne peut pas initialiser directement la requête ici ca fait planter le client
        $this->environnement = $environment;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'valuelist' => new \Twig_Function_Method($this, 'getValuelist', array('is_safe' => array('html'))),
            'value' => new \Twig_Function_Method($this, 'getValue', array('is_safe' => array('html')))
        );
    }

    /**
     * @param $category
     * @param  null                                         $locale
     * @param  string                                       $parent
     * @return null|\Nuxia\ValueListBundle\Entity\Valuelist
     */
    public function getValuelist($category, $locale = null, $parent = 'null')
    {
        return $this->valuelistManager->getValuelist(
            $locale === null ? $this->getLocale() : $locale, $category, $parent
        );
    }

    /**
     * @param $category
     * @param $code
     * @param  null   $locale
     * @param  string $parent
     * @return array  : array('label' => 'label', 'value' => 0)
     */
    public function getValue($category, $code, $locale = null, $parent = 'null')
    {
        $value = $this->valuelistManager->getValue(
            $locale === null ? $this->getLocale() : $locale, $category, $code, $parent
        );
        if ($value === null) {
            return array(
                'label' => $category . '/' . $code . '/label',
                'value' => $category . '/' . $code. '/value'
            );
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'valuelist';
    }

    /**
     * @return string
     */
    private function getLocale()
    {
        if ($this->locale === null) {
            $globals = $this->environnement->getGlobals();
            $this->locale = $globals['app']->getRequest()->getLocale();
        }

        return $this->locale;
    }
}
