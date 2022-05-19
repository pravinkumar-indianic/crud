<?php 

namespace Pravin\Crud;
use Illuminate\Support\ServiceProvider;

/**
 * 
 */
class CRUDServiceProvider extends ServiceProvider
{
	
	/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
	        __DIR__.'/config/crud.php' => config_path('crud.php')
	    ]);
    }
}