<?php 

namespace Pravin\Crud;
use Illuminate\Support\ServiceProvider;
use Pravin\Crud\Console\Commands\MvcCommand;
use Pravin\Crud\Console\Commands\RepositoryCommand;
use Pravin\Crud\Console\Commands\ViewCommand;
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
        $this->commands([
            MvcCommand::class,
            RepositoryCommand::class,
            ViewCommand::class,
        ]);
    }
}