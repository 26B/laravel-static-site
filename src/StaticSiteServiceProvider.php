<?php

namespace TwentySixB\LaravelStaticSite;

use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\LaravelPackageTools\Package;
use TwentySixB\LaravelStaticSite\Console\Commands\Assets;
use TwentySixB\LaravelStaticSite\Console\Commands\Site;

/**
 * Package Service Provider
 *
 */
class StaticSiteServiceProvider extends PackageServiceProvider
{

    /**
     * @inheritDoc
     *
     * @param Package $package
     * @return void
     */
    public function configurePackage(Package $package) : void
    {
        $package->name('laravel-static-site')
            ->hasConfigFile()
            ->hasCommands([
                Assets::class,
                Site::class,
            ]);
    }
}
