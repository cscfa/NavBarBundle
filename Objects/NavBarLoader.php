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

use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bridge\Monolog\Logger;
use Cscfa\Bundle\NavBarBundle\Objects\BundleCollection;
use Symfony\Component\Yaml\Parser;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Bundle\FrameworkBundle\Routing\Router;

/**
 * NavBarLoader class.
 *
 * The NavBarLoader class define
 * methods to load the navbar
 * informations.
 *
 * @category Object
 * @package  CscfaNavBarBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class NavBarLoader
{
    /**
     * BUNDLE_PATH
     * 
     * The bundle path where
     * find the navbar config
     * file
     * 
     * @var string
     */
    const BUNDLE_PATH = "/Resources/config/navbar.yml";
    
    /**
     * DEFAULT_POSITION
     * 
     * The default position
     * of the navbar elements
     * 
     * @var integer
     */
    const DEFAULT_POSITION = 1;
    
    /**
     * Bundles
     * 
     * A set of bundles that
     * contain a navbar config
     * file
     * 
     * @var BundleCollection
     */
    protected $bundles;
    /**
     * Logger
     * 
     * The application logger
     * service that log the
     * potentials warnings
     * 
     * @var Logger
     */
    protected $logger;
    
    /**
     * Base path
     * 
     * The base path whence
     * fing the definitions
     * files of the navbar
     * in each bundles
     * 
     * @var string
     */
    protected $basePath;
    
    /**
     * Default position
     * 
     * The default position
     * of the navbar element
     * 
     * @var integer
     */
    protected $defaultPosition;
    
    /**
     * Set arguments
     * 
     * Initialize the service
     * variables
     * 
     * @param KernelInterface $kernel - the application kernel
     * @param Logger          $logger - the application logger
     * @param array           $config - the bundle configuration
     */
    public function setArguments(KernelInterface $kernel, Logger $logger, $config){
        $this->logger = $logger;
        $this->bundles = new BundleCollection();
        
        if ($config['files_path'] !== null) {
            $this->basePath = $config['files_path'];
        } else {
            $this->basePath = self::BUNDLE_PATH;
        }
        
        if ($config['default_position'] !== null) {
            $this->defaultPosition = $config['default_position'];
        } else {
            $this->defaultPosition = self::DEFAULT_POSITION;
        }

        $this->storeBundles($kernel);
    }
    
    /**
     * Store bundles
     * 
     * This method will store
     * the application bundles
     * that contain navbar 
     * config files
     * 
     * @param KernelInterface $kernel - the application kernel
     * 
     * @return NavBarLoader - the current instance
     */
    protected function storeBundles(KernelInterface $kernel){
        foreach ($kernel->getBundles() as $bundle) {
            if ($bundle instanceof Bundle) {
                $file = $bundle->getPath().$this->basePath;
                if (is_file($file) && is_readable($file)) {
                    $this->bundles->add($bundle);
                } else if(is_file($file) && !is_readable($file)) {
                    $this->logger->warning("File ".$file." is not readable");
                }
            }
        }
        
        return $this;
    }
    
    /**
     * Build navbar
     * 
     * Return a navbar element
     * build from the application
     * navbar configuration yaml 
     * files
     * 
     * @param Router $router - the application router service
     * 
     * @return NavBar - the builded navbar
     */
    public function buildNavbar(Router $router){
        
        $bundlesNames = $this->bundles->getNames();
        $config = array();
        
        foreach ($bundlesNames as $name) {
            $bundle = $this->bundles->get($name);
            
            $configFilePath = $bundle->getPath().$this->basePath;
            $yaml = new Parser();
            
            $navbarConfig = $yaml->parse(file_get_contents($configFilePath));
            $config = array_merge($config, $navbarConfig["navbar"]);
        }
        
        $navBar = new NavBar();
        $navBar->parseArray($config, $router, $this->defaultPosition);
        return $navBar;
    }
    
}