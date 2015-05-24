<?php namespace Ems\App\Providers;

use Illuminate\Support\ServiceProvider;

class PackageServiceProvider extends ServiceProvider
{

    protected $packageNS = 'ems';

    protected $packagePath = '';

    public function boot()
    {
        $this->loadTranslationsFrom(
            $this->resourcePath('lang'),
            $this->packageNS
        );
    }

    public function register()
    {
    }

    protected function resourcePath($dir='')
    {
        $resourcePath = $this->packagePath('resources');

        if ($dir) {
            return "$resourcePath/$dir";
        }

        return $resourcePath;
    }

    protected function packagePath($dir='')
    {
        if (!$this->packagePath) {
            $this->packagePath = realpath(__DIR__.'/../../../../');
        }
        if ($dir) {
            return $this->packagePath . "/$dir";
        }
        return $this->packagePath;
    }

}