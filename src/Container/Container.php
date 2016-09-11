<?php

namespace MyRightCapital\Container;

use ArrayAccess;
use Closure;

class Container implements ArrayAccess, ContainerInterface
{
    /**
     * The current globally available container.
     * 
     * @var
     */
    protected static $instance;
    /**
     * The container's bindings.
     *
     * @var array
     */
    protected $bindings = [];

    /**
     * The container's shared instances.
     *
     * @var array
     */
    protected $instances = [];

    /**
     * The registered type aliases.
     *
     * @var array
     */
    protected $aliases = [];

    /**
     * Array of the types that have been resolved.
     * 
     * @var array
     */
    protected $resolved = [];
    
    /**
     * All of the registered rebound callbacks.
     * 
     * @var array
     */
    protected $reboundCallbacks = [];

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        // TODO: Implement offsetExists() method.
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        // TODO: Implement offsetGet() method.
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        // TODO: Implement offsetSet() method.
    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        // TODO: Implement offsetUnset() method.
    }

    
    
    
////////////////////////////////////////////////////////////////////

    /**
     * Normalize the given class name by removing leading slashes.
     * e.g. \MyRightCapital\Container
     *
     * @param mixed $service
     *
     * @return mixed
     */
    public function normalize($service)
    {
        return is_string($service) ? ltrim($service, '\\') : $service;
    }

    /**
     * Determine if a given string is an alias.
     *
     * @param string $name
     *
     * @return bool
     */
    public function isAlias($name)
    {
        return isset($this->aliases[$this->normalize($name)]);
    }

    /**
     * Extract the type and alias from a given definition.
     * 
     * @param array $definition
     *
     * @return array
     */
    public function extractAlias(array $definition)
    {
        return [key($definition), current($definition)];
    }

    /**
     * Drop all of the stale instances and aliases.
     * 
     * @param string $abstract
     */
    protected function dropStaleInstances($abstract)
    {
        unset($this->instances[$abstract], $this->aliases[$abstract]);
    }

    /**
     * Get the Closure to be used when building a type.
     * 
     * @param string $abstract
     * @param string $concrete
     *
     * @return \Closure
     */
    protected function getClosure($abstract, $concrete)
    {
        return function ($c, $parameters = []) use ($abstract, $concrete) {
            $method = ($abstract === $concrete) ? 'build' : 'make';
            
            return $c->$method($concrete, $parameters);
        };
    }

    /**
     * Get the alias for an abstract if available.
     * 
     * @param string $abstract
     *
     * @return string
     */
    protected function getAlias($abstract)
    {
        if (! isset($this->aliases[$abstract])) {
            return $abstract;
        }
        
        return $this->getAlias($this->aliases[$abstract]);
    }

    /**
     * Fire the 'rebound' callbacks for the given abstract type.
     * 
     * @param string $abstract
     * 
     * @return void
     */
    protected function rebound($abstract)
    {
        $instance = $this->make($abstract);

        foreach ($this->getReboundCallbacks($abstract) as $callback) {
            call_user_func($callback, $this, $instance);
        }
    }

    /**
     * Get the rebound callbacks for a given type.
     * 
     * @param string $abstract
     *
     * @return array|mixed
     */
    protected function getReboundCallbacks($abstract)
    {
        return $this->reboundCallbacks[$abstract] ?? [];
    }

    /**
     * Get the global static instance of the container. 
     * 
     * @return static
     */
    public static function getInstance()
    {
        return static::$instance;
    }

    /**
     * Set the shared instance of the container.
     * 
     * @param \MyRightCapital\Container\ContainerInterface $container
     * 
     * @return void
     */
    public static function setInstance(ContainerInterface $container)
    {
        static::$instance = $container;
    }
    
//////////////////////////////////////////////////////////////////
    



    /**
     * Determine if the given type has been bound.
     *
     * @param string $abstract
     *
     * @return bool
     */
    public function bound($abstract)
    {
        $abstract = $this->normalize($abstract);

        return isset($this->bindings[$abstract]) || isset($this->instances[$abstract]) || $this->isAlias($abstract);
    }

    /**
     * Alias a type to a different name.
     *
     * @param string $abstract
     * @param string $alias
     *
     * @return void
     */
    public function alias($abstract, $alias)
    {
        // TODO: Implement alias() method.
        $this->aliases[$alias] = $this->normalize($abstract);
    }

    /**
     * Assign a set of tags to a given binding.
     *
     * @param array|string $abstracts
     * @param array|mixed  ...$tags
     *
     * @return void
     */
    public function tag($abstracts, $tags)
    {
        // TODO: Implement tag() method.
    }

    /**
     * Resolve all of the bindings for a given tag.
     *
     * @param array $tag
     *
     * @return array
     */
    public function tagged($tag)
    {
        // TODO: Implement tagged() method.
    }

    /**
     * Register a binding with the container.
     *
     * @param string|array         $abstract
     * @param \Closure|string|null $concrete
     * @param bool                 $shared
     *
     * @return void
     */
    public function bind($abstract, $concrete = null, $shared = null)
    {
        $abstract = $this->normalize($abstract);
        
        $concrete = $this->normalize($concrete);
        
        if (is_array($abstract)) {
            list($abstract, $alias) = $this->extractAlias($abstract);
            
            $this->alias($abstract, $alias);
        }
        
        $this->dropStaleInstances($abstract);
        
        if (is_null($concrete)) {
            $concrete = $abstract;
        }
        
        if (! $concrete instanceof Closure) {
            $concrete = $this->getClosure($abstract, $concrete);
        }
        
        $this->bindings[$abstract] = compact('concrete', 'shared');
        
        if ($this->resolved($abstract)) {
            $this->rebound($abstract);
        }
    }

    /**
     * Register a binding if it hasn't already been registered.
     *
     * @param string               $abstract
     * @param \Closure|string|null $concrete
     * @param bool                 $shared
     *
     * @return void
     */
    public function bindIf($abstract, $concrete = null, $shared = null)
    {
        if (! $this->bound($abstract)) {
            $this->bind($abstract, $concrete, $shared);
        }
    }

    /**
     * Register a shared binding in the container.
     *
     * @param string|array         $abstract
     * @param \Closure|string|null $concrete
     *
     * @return void
     */
    public function singleton($abstract, $concrete = null)
    {
        $this->bind($abstract, $concrete, true);
    }

    /**
     * "Extend" an type in the container.
     *
     * @param string   $abstract
     * @param \Closure $closure
     *
     * @throws \InvalidArgumentException
     *
     * @return void
     */
    public function extend($abstract, Closure $closure)
    {
        // TODO: Implement extend() method.
    }

    /**
     * Register an existing instance as shared in the container.
     *
     * @param $abstract
     * @param $instance
     *
     * @return void
     */
    public function instance($abstract, $instance)
    {
        // TODO: Implement instance() method.
        $abstract = $this->normalize($abstract);

        if (is_array($abstract)) {
            list($abstract, $alias) = $this->extractAlias($abstract);

            $this->alias($abstract, $alias);
        }
        
        unset($this->aliases[$abstract]);
        
        $bound = $this->bound($abstract);
        
        $this->instances[$abstract] = $instance;
        
        if ($bound) {
            $this->rebound($abstract);
        }
    }

    /**
     * Define a contextual binding.
     *
     * @param string $concrete
     *
     * @return ContextualBindingBuilderInterface
     */
    public function when($concrete)
    {
        // TODO: Implement when() method.
    }

    /**
     * Resolve the given type from the container.
     *
     * @param string $abstract
     * @param array  $parameters
     *
     * @return mixed
     */
    public function make($abstract, array $parameters = [])
    {
        // TODO: Implement make() method.
        
    }

    /**
     * Call the given Closure/class@method and inject its dependencies.
     *
     * @param callable|string $callback
     * @param array           $parameters
     * @param string|null     $defaultMethod
     *
     * @return mixed
     */
    public function call($callback, array $parameters = [], $defaultMethod = null)
    {
        // TODO: Implement call() method.
    }

    /**
     * Determine if the given type has been resolved.
     *
     * @param string $abstract
     *
     * @return bool
     */
    public function resolved($abstract)
    {
        $abstract = $this->normalize($abstract);
        
        if ($this->isAlias($abstract)) {
            $abstract = $this->getAlias($abstract);
        }
        
        return isset($this->resolved[$abstract]) || isset($this->instances[$abstract]);
    }

    /**
     * Register a new resolving callback.
     *
     * @param string        $abstract
     * @param \Closure|null $callback
     *
     * @return void
     */
    public function resolving($abstract, Closure $callback = null)
    {
        // TODO: Implement resolving() method.
    }

    /**
     * Register a new after resolving callback.
     *
     * @param string        $abstract
     * @param \Closure|null $callback
     *
     * @return void
     */
    public function afterResolving($abstract, Closure $callback = null)
    {
        // TODO: Implement afterResolving() method.
    }

    /**
     * Dynamically access container services. 
     * 
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this[$key];
    }

    /**
     * Dynamically set container services.
     * 
     * @param $key
     * @param $value
     */
    public function __set($key, $value)
    {
        $this[$key] = $value;
    }
}