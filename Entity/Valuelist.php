<?php

namespace Nuxia\ValuelistBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\PropertyAccess\PropertyAccess;

/**
 * Valuelist
 */
class Valuelist
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $code;

    /**
     * @var string
     */
    private $language;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $label;

    /**
     * @var float
     */
    private $value;

    /**
     * @var Valuelist
     */
    private $parent;

    /**
     * @var ArrayCollection
     */
    private $children;

    public function __construct()
    {
        $this->language = 'default';
        $this->children = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set code
     *
     * @param  string    $code
     * @return Valuelist
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set language
     *
     * @param  string    $language
     * @return Valuelist
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return string
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set category
     *
     * @param  string    $category
     * @return Valuelist
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set label
     *
     * @param  string    $label
     *
     * @return Valuelist
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     *
     * @param  float $value
     *
     * @return Valuelist
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param Valuelist $parent
     *
     * @return Valuelist
     */
    public function setParent(Valuelist $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \Nuxia\ValuelistBundle\Entity\Valuelist
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param  Valuelist $child
     *
     * @return Valuelist
     */
    public function addChild(Valuelist $child)
    {
        if ($child->getParent() === null) {
            $child->setParent($this);
        }

        $this->children[] = $child;

        return $this;
    }

    /**
     * @param Valuelist $child
     *
     * @return Valuelist
     */
    public function removeChild(Valuelist $child)
    {
        $this->children->removeElement($child);

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param ArrayCollection $children
     *
     * @return Valuelist
     */
    public function setChildren(ArrayCollection $children)
    {
        foreach ($children as $child) {
            $this->addChild($child);
        }

        return $this;
    }

    /**
     * @param string $input
     * @param array  $fields
     *
     * @return Valuelist
     */
    public function fromArrayOrObject($input, array $fields = array())
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $isArray = is_array($input);

        if ($isArray && count($fields) === 0) {
            $fields = array_keys($input);
        }

        foreach ($fields as $field) {
            $path = $isArray ? '[' . $field . ']' : $field;
            $accessor->setValue($this, $field, $accessor->getValue($input, $path));
        }

        return $this;
    }
}
