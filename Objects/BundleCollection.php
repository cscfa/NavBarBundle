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

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * BundleCollection class.
 *
 * The BundleCollection class 
 * store a set of FrameworkBundle
 * instance
 *
 * @category Object
 * @package  CscfaNavBarBundle
 * @author   Matthieu VALLANCE <matthieu.vallance@cscfa.fr>
 * @license  http://opensource.org/licenses/MIT MIT
 * @link     http://cscfa.fr
 */
class BundleCollection
{

    /**
     * Bundles
     * 
     * The array of bundles
     * 
     * @var array
     */
    protected $bundles;

    /**
     * Constructor
     * 
     * Default constructor
     * 
     * @param array $bundles - an optional array of bundle
     */
    public function __construct(array $bundles = null)
    {
        if ($bundles !== null) {
            $this->bundles = $bundles;
        } else {
            $this->bundles = array();
        }
    }

    /**
     * Add
     * 
     * Add a FrameworkBundle
     * to the collection
     * 
     * @param FrameworkBundle $bundle - the FrameworkBundle to add
     * 
     * @return BundleCollection - the current instance
     */
    public function add(Bundle $bundle)
    {
        if (! $this->has($bundle->getName())) {
            $this->bundles[] = $bundle;
        }
        
        return $this;
    }

    /**
     * Remove
     * 
     * Remove a FrameworkBundle
     * from the collection
     * 
     * @param string $name - the FrameworkBundle name to remove
     * 
     * @return BundleCollection - the current instance
     */
    public function remove($name){
        if ($this->has($name)) {
            unset($this->bundles[$this->getKey($name)]);
        }
        
        return $this;
    }

    /**
     * Get
     * 
     * Return a FrameworkBundle
     * from the collection
     * 
     * @param string $name - the FrameworkBundle name to return
     * 
     * @return FrameworkBundle - the FrameworkBundle
     */
    public function get($name){
        return $this->bundles[$this->getKey($name)];
    }

    /**
     * Get key
     * 
     * Return the FrameworkBundle
     * position into the bundle
     * array
     * 
     * @param string $name - the FrameworkBundle name to return
     * 
     * @return Integer - the FrameworkBundle position
     */
    protected function getKey($name){
        foreach ($this->bundles as $key=>$bundle) {
            if ($bundle->getName() == $name) {
                return $key;
            }
        }
    }

    /**
     * Has
     * 
     * Return the FrameworkBundle
     * storage state
     * 
     * @param string $name - the FrameworkBundle name to check
     * 
     * @return boolean - the FrameworkBundle storage state
     */
    public function has($name)
    {
        return in_array($name, $this->getNames());
    }

    /**
     * Get names
     * 
     * Return all of the stored
     * FrameworkBundle names
     * 
     * @return array - an array of the FrameworkBundle names
     */
    public function getNames()
    {
        $names = array();
        
        foreach ($this->bundles as $bundle) {
            $names[] = $bundle->getName();
        }
        
        return $names;
    }

    /**
     * To array
     * 
     * Return all of the stored
     * FrameworkBundle
     * 
     * @return array - an array of the FrameworkBundle
     */
    public function toArray(){
        return $this->bundles;
    }
}