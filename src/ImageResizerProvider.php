<?php

namespace jilsonasis\ImageResizer;

use Illuminate\Support\ServiceProvider;

class ImageResizerProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('jilsonasis\ImageResizer\ImageResizer');
    }
}
