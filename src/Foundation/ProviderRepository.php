<?php

namespace MyRightCapital\Foundation;

use MyRightCapital\Filesystem\Filesystem;

class ProviderRepository
{

    /**
     * @var \MyRightCapital\Foundation\ApplicationInterface
     */
    private $app;
    /**
     * @var \MyRightCapital\Filesystem\Filesystem
     */
    private $files;
    /**
     * The path to the manifest file.
     *
     * @var string
     */
    private $manifestPath;

    /**
     * Create a new service repository instance.
     *
     * @param \MyRightCapital\Foundation\ApplicationInterface $app
     * @param \MyRightCapital\Filesystem\Filesystem           $files
     * @param string                                          $manifestPath
     */
    public function __construct(ApplicationInterface $app, Filesystem $files, $manifestPath)
    {
        $this->app          = $app;
        $this->files        = $files;
        $this->manifestPath = $manifestPath;
    }

    /**
     * Register the application service providers.
     *
     * @param array $providers
     */
    public function load(array $providers)
    {
        $manifest = $this->loadManifest();

        if ($this->shouldRecompile($manifest, $providers)) {
            $manifest = $this->compileManifest($providers);
        }

        foreach ($manifest['when'] as $provider => $events) {
            $this->registerLoadEvents($provider, $events);
        }

        foreach ($manifest['eager'] as $provider) {
            $this->app->register($this->createProvider($provider));
        }

        $this->app->addDeferredServices($manifest['deferred']);
    }
}