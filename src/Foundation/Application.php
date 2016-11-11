<?php

namespace MyRightCapital\Foundation;

use MyRightCapital\Container\Container;
use MyRightCapital\Filesystem\Filesystem;
use MyRightCapital\Support\Arr;
use MyRightCapital\Support\Str;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class Application extends Container implements ApplicationInterface, HttpKernelInterface
{
    /**
     * The application version number.
     *
     * @var string
     */
    const VERSION = '1.0';

    /**
     * The base path for the application installation.
     *
     * @var string
     */
    protected $basePath;
    /**
     * Indicates if the application has booted.
     * 
     * @var bool
     */
    protected $booted = false;
    /**
     * All of the registered service providers.
     * 
     * @var array
     */
    protected $serviceProviders = [];

    ///////////////////////////////////////

    /**
     * Set the base path for the application.
     *
     * @param string $basePath
     *
     * @return $this
     */
    public function setBasePath($basePath)
    {
        $this->basePath = rtrim($basePath, '\/');

        $this->bindPathsInContainer();

        return $this;
    }

    public function bindPathsInContainer()
    {
        $this->instance('path', $this->path());
        $this->instance('path.base', $this->basePath());
        $this->instance('path.lang', $this->langPath());
        $this->instance('path.config', $this->configPath());
        $this->instance('path.public', $this->publicPath());
        $this->instance('path.storage', $this->storagePath());
        $this->instance('path.database', $this->databasePath());
        $this->instance('path.bootstrap', $this->bootstrapPath());
    }

    public function path()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'app';
    }

    public function langPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'lang';
    }

    public function configPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'config';
    }

    public function publicPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'public';
    }

    public function storagePath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'storage';
    }

    public function databasePath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'database';
    }

    public function bootstrapPath()
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'bootstrap';
    }

    /**
     * Get the registered service provider if th exists.
     * 
     * @param \MyRightCapital\Support\ServiceProvider|string $provider
     * 
     * @return \MyRightCapital\Support\ServiceProvider|null
     */
    public function getProvider($provider)
    {
        // if $provider is an object
        $name = is_string($provider) ? $provider : get_class($provider);
        
        return Arr::first($this->serviceProviders, function ($key, $value) use ($name) {
            return $value instanceof $name;
        });
    }
    ///////////////////////////////////////

    public function __construct($basePath)
    {

        if ($basePath) {
            $this->setBasePath($basePath);
        }
    }

    /**
     * Handles a Request to convert it to a Response.
     *
     * When $catch is true, the implementation must catch all exceptions
     * and do its best to convert them to a Response instance.
     *
     * @param Request $request A Request instance
     * @param int     $type    The type of the request
     *                         (one of HttpKernelInterface::MASTER_REQUEST or HttpKernelInterface::SUB_REQUEST)
     * @param bool    $catch   Whether to catch exceptions or not
     *
     * @return Response A Response instance
     *
     * @throws \Exception When an Exception occurs during processing
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        // TODO: Implement handle() method.
    }

    /**
     * Get the version number of the application.
     *
     * @return string
     */
    public function version()
    {
        return static::VERSION;
    }

    /**
     * Get the base path of the application installation.
     *
     * @return string
     */
    public function basePath()
    {
        return $this->basePath;
    }

    //?? $this['env']
    /**
     * Get or check the current application environment.
     *
     * @param mixed
     *
     * @return string
     */
    public function environment()
    {
        if (func_num_args() > 0) {
            $patterns = is_array(func_get_arg(0)) ? func_get_arg(0) : func_get_args();

            foreach ($patterns as $pattern) {
                if (Str::is($pattern, $this['env'])) {
                    return true;
                }
            }

            return false;
        }
        
        return $this['env'];
    }

    /**
     * Determine if the application is currently down for maintenance.
     *
     * @return bool
     */
    public function isDownForMaintenance()
    {
        return file_exists($this->storagePath() . '/framework/down');
    }

    /**
     * Register all of the configured providers.
     *
     * @return void
     */
    public function registerConfiguredProviders()
    {
        $manifestPath = $this->getCachedServicesPath();
        
        (new ProviderRepository($this, new Filesystem, $manifestPath))
            ->load($this->config['app.providers']);
    }

    /**
     * Register a service provider with the application.
     *
     * @param string|\MyRightCapital\Support\ServiceProvider $provider
     * @param array                                          $options
     * @param bool                                           $force
     *
     * @return \MyRightCapital\Support\ServiceProvider
     */
    public function register($provider, $options = [], $force = false)
    {
        // TODO: Implement register() method.
        if (($registered = $this->getProvider($provider)) && ! $force) {
            return $registered;
        }
        
        if (is_string($provider)) {
            $provider = $this->resolveProviderClass($provider);
        }
        
        $provider->register();

        foreach ($options as $key => $value) {
            $this[$key] = $value;
        }
        
        $this->markAsRegistered($provider);
        
        if ($this->booted) {
            $this->bootProvider($provider);
        }
        
        return $provider;
    }

    /**
     * Register a deferred provider and service.
     *
     * @param string $provider
     * @param string $service
     *
     * @return void
     */
    public function registerDeferredProvider($provider, $service = null)
    {
        // TODO: Implement registerDeferredProvider() method.
    }

    /**
     * Boot the application's service provider.
     *
     * @return void
     */
    public function boot()
    {
        // TODO: Implement boot() method.
    }

    /**
     * Register a new boot listener.
     *
     * @param mixed $callback
     *
     * @return void
     */
    public function booting($callback)
    {
        // TODO: Implement booting() method.
    }

    /**
     * Register a new 'booted' listener.
     *
     * @param mixed $callback
     *
     * @return void
     */
    public function booted($callback)
    {
        // TODO: Implement booted() method.
    }

    /**
     * Get the path to the cached 'compiled.php' file.
     *
     * @return string
     */
    public function getCachedCompilePath()
    {
        return $this->bootstrapPath() . DIRECTORY_SEPARATOR .'/cache/compiled.php';
    }

    /**
     * Get the path to the cached 'services.php' file
     *
     * @return string
     */
    public function getCachedServicesPath()
    {
        return $this->bootstrapPath() . DIRECTORY_SEPARATOR .'/cache/services.php';
    }

}