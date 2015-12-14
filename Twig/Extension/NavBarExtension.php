<?php
/**
 * This file is a part of CSCFA navbar project.
 * 
 * The navbar project is a symfony bundle written in php
 * with Symfony2 framework.
 * 
 * PHP version 5.5
 * 
 * @category Twig extension
 * @package  CscfaNavBarBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @filesource
 * @link     http://cscfa.fr
 */
namespace Cscfa\Bundle\NavBarBundle\Twig\Extension;

use Cscfa\Bundle\NavBarBundle\Objects\NavBarLoader;
use Symfony\Component\Security\Core\SecurityContext;
use Cscfa\Bundle\NavBarBundle\Objects\NavBar;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Cscfa\Bundle\NavBarBundle\Objects\NavBarElement;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * NavBarExtension class.
 *
 * The NavBarExtension class define
 * the twig extension to display
 * the CSManager navigation bar.
 *
 * @category Twig extension
 * @package  CscfaNavBarBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class NavBarExtension extends \Twig_Extension
{

    const DEFAULT_TEMPLATE = "CscfaNavBarBundle:navbar:navbar.html.twig";
    const DEFAULT_CHILD_TEMPLATE = "CscfaNavBarBundle:navbar:child.html.twig";
    
    /**
     * User
     * 
     * The current user
     * 
     * @var User
     */
    protected $user;
    
    /**
     * Router
     * 
     * The application router service
     * 
     * @var Router
     */
    protected $router;
    
    /**
     * Loader
     * 
     * The navbar loader service
     * 
     * @var NavBarLoader
     */
    protected $loader;
    
    /**
     * Template
     * 
     * The main template to use
     * 
     * @var string
     */
    protected $template;
    
    /**
     * Child template
     * 
     * The template to use to
     * render child
     * 
     * @var string
     */
    protected $childTemplate;
    
    /**
     * Constructor
     * 
     * Default constructor
     * 
     * @param NavBarLoader    $loader  - the navbar loader service
     * @param SecurityContext $context - the security context service
     * @param Router          $router  - the application router service
     */
    public function setArguments(NavBarLoader $loader, SecurityContext $context, Router $router, $config)
    {
        $template = $config['template'];
        $childTemplate = $config['child_template'];
        
        if ($template !== null) {
            $this->template = $template;
        } else {
            $this->template = self::DEFAULT_TEMPLATE;
        }
        
        if ($childTemplate !== null) {
            $this->childTemplate = $childTemplate;
        } else {
            $this->childTemplate = self::DEFAULT_CHILD_TEMPLATE;
        }
        
        $this->router = $router;
        $this->loader = $loader;
        
        if (method_exists($context->getToken(), 'getUser') && 
            is_object($context->getToken()->getUser()) &&
            method_exists($context->getToken(), 'getRoles')) {
            $this->user = $context->getToken()->getUser();
        } else {
            $this->user = null;
        }
    }
    
    /**
     * Get function
     * 
     * Return the mapping of
     * the function name and
     * class methods
     * 
     * @see Twig_Extension::getFunctions()
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('renderNavbar', array($this, 'renderNavbar'), array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            )
        );
    }
    
    /**
     * Get filters
     * 
     * Return the mapping of
     * the filters name and
     * class methods
     * 
     * @see Twig_Extension::getFilters()
     */
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('navbarChild', array($this, 'renderChild'), array(
                    'is_safe' => array('html'),
                    'needs_environment' => true
                )
            ),
        );
    }
    
    /**
     * Render navbar
     * 
     * Return a rendered twig
     * view that contain the
     * html navbar
     * 
     * @return string - The rendered template
     */
    public function renderNavbar(\Twig_Environment $twig){
        
        $navbar = $this->loader->buildNavbar($this->router);
        $elements = $navbar->getElements();
        
        if ($this->user !== null) {
            $roles = $this->user->getRoles();
        } else {
            $roles = array();
        }
        
        foreach ($elements as $key=>$element) {
            if (!$this->checkRole($element, $roles)) {
                unset($elements[$key]);
            }
        }
        
        $navbar->setElements($elements);
        
        return $twig->render($this->template, array("navbar"=>$navbar->getHead()));
    }
    
    public function renderChild(\Twig_Environment $twig, NavBarElement $child, $nestedLevel){
        return $twig->render($this->childTemplate, array("element"=>$child, "nestedLevel"=>($nestedLevel + 1)));
    }
    
    /**
     * Check role
     * 
     * Verify if the user has
     * granted the needed roles
     * of the element and it's 
     * childs and remove it if 
     * not
     * 
     * @param NavBarElement $element  - the element to valid
     * @param array         $userRole - the user roles array
     * 
     * @return boolean
     */
    protected function checkRole(&$element, $userRole){
        if($element instanceof NavBarElement){

            $elementRoles = $element->getRoles();
            if (!is_array($elementRoles)) {
                $elementRoles = array();
            }
            
            foreach ($elementRoles as $role) {
                if (!in_array($role, $userRole)) {
                    return false;
                }
            }
            
            $childs = $element->getChild();
            if(!empty($childs)){
                foreach ($childs as $key=>$child) {
                    if(!$this->checkRole($child, $userRole)){
                        unset($childs[$key]);
                    }
                }
            }
            $element->setChild($childs);
        }
        
        return true;
    }

    /**
     * Get name
     * 
     * Return the current extension name
     * 
     * @see Twig_ExtensionInterface::getName()
     * @return string
     */
    public function getName()
    {
        return 'cs_navbar_extension';
    }
}