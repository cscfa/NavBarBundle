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
     * Ordered elements
     * 
     * This variable contain
     * the navbar elements
     * ordered by position
     * 
     * @var array
     */
    protected $orderedElements;
    
    /**
     * Default position
     * 
     * This variable indicate
     * the default position
     * of the element
     * 
     * @var integer
     */
    protected $defaultPosition;

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
     * Get ordered elements
     * 
     * Return the navbar elements
     * ordered by their positions
     * 
     * @return array
     */
    public function getOrderedElements()
    {
        return $this->orderedElements;
    }

    /**
     * Set ordered elements
     * 
     * Set the navbar elements
     * ordered by their positions
     * 
     * @param array $orderedElements - the navbar ordered elements
     * 
     * @return NavBar - the current instance
     */
    public function setOrderedElements(array $orderedElements)
    {
        $this->orderedElements = $orderedElements;
        return $this;
    }

    /**
     * Get default position
     * 
     * Return the navbar elements
     * default position
     * 
     * @return integer
     */
    public function getDefaultPosition()
    {
        return $this->defaultPosition;
    }

    /**
     * Set default position
     * 
     * Set the navbar elements
     * default position
     * 
     * @param integer $defaultPosition - the navbar default position
     * 
     * @return NavBar - the current instance
     */
    public function setDefaultPosition($defaultPosition)
    {
        $this->defaultPosition = $defaultPosition;
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
    public function getHead()
    {
        $elements = array();
        
        foreach ($this->elements as $element) {
            $elements[$element->getName()] = $element;
        }
        
        foreach ($this->elements as $element) {
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
        
        $resultNavBar = new NavBar();
        $resultNavBar->setElements($elements);
        $resultNavBar->setOrderedElements($this->orderElements($elements));
        
        return $resultNavBar;
    }
    
    /**
     * Order elements
     * 
     * Order the navbar
     * elements by their
     * position
     * 
     * @param unknown $elements
     * @return multitype:\Cscfa\Bundle\NavBarBundle\Objects\NavBarElement 
     */
    protected function orderElements($elements){
        $order = array();
        $result = array();
        
        foreach ($elements as $element) {
            if($element instanceof NavBarElement){
                if($element->getPosition() !== null){
                    $position = $element->getPosition();
                }else{
                    $position = $this->defaultPosition;
                }
                
                if (!isset($order[$position])) {
                    $order[$position] = array();
                }
                
                if ($element->getChild() !== null) {
                    $element->setChild($this->orderElements($element->getChild()));
                }
                
                $order[$position][] = $element;
            }
        }
        //sort array by keys
        ksort($order);
        
        foreach ($order as $orderedElements) {
            foreach ($orderedElements as $element) {
                $result[] = $element;
            }
        }
        
        return $result;
    }

    /**
     * Parse array
     * 
     * Parse an array given by
     * a yaml parser
     * 
     * @param array   $navbar          - the navbar array rendered by yaml parser
     * @param router  $router          - the application router
     * @param integer $defaultPosition - the default navbar element position
     * 
     * @throws \Exception
     * @return NavBar - the current instance
     */
    public function parseArray($navbar, Router $router, $defaultPosition)
    {
        $this->defaultPosition = $defaultPosition;
        
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
            case 'position':
                $element->setPosition($value);
                break;
            default:
                throw new \Exception(sprintf("The '%s' key for navbar element does not exist. Allowed : 'label', 'roles', 'path', 'parent', 'url', 'options', 'position'", $value), 500);
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
    protected function generateRoute($name, $value, Router $router)
    {
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
