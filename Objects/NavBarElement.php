<?php
/**
 * This file is a part of CSCFA navbar project.
 * 
 * The navbar project is a symfony bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Object
 * @package  CscfaNavBarBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\NavBarBundle\Objects;

/**
 * NavBarElement class.
 *
 * The NavBarElement class 
 * contain the navbar element
 * informations.
 *
 * @category Object
 * @package  CscfaNavBarBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class NavBarElement
{
    /**
     * Name
     * 
     * The element name
     * 
     * @var string
     */
    protected $name;
    /**
     * Label
     * 
     * The element label
     * 
     * @var string
     */
    protected $label;
    /**
     * Roles
     * 
     * The element roles
     * 
     * @var array
     */
    protected $roles;
    /**
     * Child
     * 
     * The element childs
     * 
     * @var array
     */
    protected $child;
    /**
     * Path
     * 
     * The element path
     * 
     * @var string
     */
    protected $path;
    /**
     * Options
     * 
     * The element options to
     * optionaly use in template
     * 
     * @var mixed
     */
    protected $options;

    /**
     * Get name
     * 
     * Return the element
     * name
     * 
     * @return string - the element name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     * 
     * Set the element
     * name
     * 
     * @param string $name - the element name
     * 
     * @return NavBarElement - the current instance
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get label
     * 
     * Return the element
     * label
     * 
     * @return string - the element label
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set label
     * 
     * Set the element
     * label
     * 
     * @param string $label - the element label
     * 
     * @return NavBarElement - the current instance
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Get roles
     * 
     * Return the element
     * roles
     * 
     * @return array - the element roles
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set roles
     * 
     * Set the element
     * roles
     * 
     * @param array $roles - the element roles
     * 
     * @return NavBarElement - the current instance
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;
        return $this;
    }

    /**
     * Add childs
     * 
     * Add a child element
     * 
     * @return NavBarElement - the current instance
     */
    public function addChild(NavBarElement $child){
        $this->child[] = $child;
        return $this;
    }

    /**
     * Get childs
     * 
     * Return the element
     * childs
     * 
     * @return array - the element childs
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * Set childs
     * 
     * Set the element
     * childs
     * 
     * @param array $child - the element childs
     * 
     * @return NavBarElement - the current instance
     */
    public function setChild($child)
    {
        $this->child = $child;
        return $this;
    }

    /**
     * Get path
     * 
     * Return the element
     * path
     * 
     * @return string - the element path
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set path
     * 
     * Set the element
     * path
     * 
     * @param string $path - the element path
     * 
     * @return NavBarElement - the current instance
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * Get options
     * 
     * Return the element
     * options
     * 
     * @return mixed - the element options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options
     * 
     * Set the element
     * options
     * 
     * @param mixed $options - the element options
     * 
     * @return NavBarElement - the current instance
     */
    public function setOptions($options)
    {
        $this->options = $options;
        return $this;
    }
}
