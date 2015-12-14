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

use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * NavBar class.
 *
 * The NavBar class
 * contain the navbar
 * informations.
 *
 * @category Object
 * @package  CscfaNavBarBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class NavBar
{

    /**
     * Elements
     * 
     * This variable contain
     * the navbar elements
     * 
     * @var array
     */
    protected $elements;

    /**
     * Constructor
     * 
     * Default constructor
     */
    public function __construct()
    {
        $this->elements = array();
    }

    /**
     * Get element
     * 
     * Return the navbar elements
     * 
     * @return array
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Set element
     * 
     * Set the navbar elements
     * 
     * @param array $elements - the navbar elements
     * 
     * @return NavBar - the current instance
     */
    public function setElements(array $elements)
    {
        $this->elements = $elements;
        return $this;
    }
 
    /**
     * Get head
     * 
     * Return the front navbar
     * elements
     * 
     * @return array
     */
    public function getHead(){
        $elements = array();
        
        foreach ($this->elements as $element) {
            $elements[$element->getName()] = $element;
        }
        
        foreach ($this->elements as $element){
            if (is_array($element->getChild())) {
                foreach ($element->getChild() as $child) {
                    if ($child instanceof NavBarElement) {
                        if (array_key_exists($child->getName(), $elements)) {
                            unset($elements[$child->getName()]);
                        }
                    }
                }
            }
        }
        
        return (new NavBar())->setElements($elements);
    }
    
    /**
     * Parse array
     * 
     * Parse an array given by
     * a yaml parser
     * 
     * @param array  $navbar - the navbar array rendered by yaml parser
     * @param router $router - the application router
     * 
     * @throws \Exception
     * @return NavBar - the current instance
     */
    public function parseArray($navbar, Router $router)
    {
        foreach ($navbar as $navbarName => $navbarConfig) {
            if (! array_key_exists($navbarName, $this->elements)) {
                $this->elements[$navbarName] = $this->createElement($navbarName, $navbarConfig, $router);
            }
            
            if (array_key_exists('parent', $navbarConfig)) {
                if (array_key_exists($navbarConfig['parent'], $this->elements)) {
                    $this->elements[$navbarConfig['parent']]->addChild($this->elements[$navbarName]);
                } else if (array_key_exists($navbarConfig['parent'], $navbar)) {
                    $this->elements[$navbarName] = $this->createElement($navbarConfig['parent'], $navbar[$navbarConfig['parent']], $router);
                    $this->elements[$navbarConfig['parent']]->addChild($this->elements[$navbarName]);
                } else {
                    throw new \Exception(sprintf("The '%s' navbar element need '%s' navbar element as parent but '%s' does not exist.", $navbarName, $navbarConfig['parent'], $navbarConfig['parent']), 500);
                }
            }
        }
        
        return $this;
    }

    /**
     * Create element
     * 
     * Create a navbar element
     * from an element array
     * configuration
     * 
     * @param string $name   - the element name
     * @param array  $array  - the element configuration
     * @param router $router - the application router
     * 
     * @throws \Exception
     * @return NavBar - the current instance
     */
    protected function createElement($name, $array, Router $router)
    {
        $element = new NavBarElement();
        $element->setName($name)->setLabel($name);
        
        foreach ($array as $key => $value) {
            switch ($key) {
            case 'label':
                $element->setLabel($value);
                break;
            case 'roles':
                $element->setRoles($value);
                break;
            case 'path':
                $element->setPath($this->generateRoute($name, $value, $router));
                break;
            case 'url':
                $element->setPath($value);
                break;
            case 'parent':
                // nothing
                break;
            case 'options':
                $element->setOptions($value);
                break;
            default:
                throw new \Exception(sprintf("The '%s' key for navbar element does not exist. Allowed : 'label', 'roles', 'path', 'parent', 'url', 'options'", $value), 500);
            }
        }
        
        return $element;
    }
    
    /**
     * Generate route
     * 
     * Generate a route to the
     * application
     * 
     * @param string $name   - the element name
     * @param array  $value  - the path informations
     * @param Router $router - the application router
     * @throws \Exception
     */
    protected function generateRoute($name, $value, Router $router){
        
        if (array_key_exists("route", $value)) {
            $route = $value['route'];
        } else {
            throw new \Exception(sprintf("The 'route' key for navbar element does not exist for '%s' path option. It required.'", $name), 500);
        }
        
        if (array_key_exists("param", $value)) {
            $param = $value['param'];
        } else {
            $param = array();
        }
        
        return $router->generate($route, $param);
    }
}
