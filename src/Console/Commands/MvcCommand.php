<?php

namespace Pravin\Crud\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MvcCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud
                            {name : The name of the CRUD.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new CRUD';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return 
     */
    public function handle()
    {
        $this->line('Starting CRUD Generation...');
        $name = $this->argument('name');
        $this->line('Creating model...');
        sleep(1);
        $this->model($name);
        $this->line('Creating view...');
        sleep(1);
        $this->call('make:view', ['name' => $name]);
        $this->line('Creating controller...');
        sleep(1);
        $this->controller($name);
        $this->line('Creating migration...');
        sleep(1);
        $this->migration($name);
        $this->line('Creating request...');
        sleep(1);
        $this->request($name,'Create');
        $this->request($name,'Update');
        $this->line('Creating repository...');
        sleep(1);
        if (strpos($name, '/')) {
            list($folder,$file) = explode('/', $name);
            $this->call('make:repository', ['name' => $file]);
        }else{
            $this->call('make:repository', ['name' => $name]);
        }
        $this->line('Creating route...');
        sleep(1);
        $this->route($name);
        $this->line('Creating language...');
        sleep(1);
        $this->language($name);
        $this->line('Creating script...');
        sleep(1);
        $this->script($name);
        $this->line('Creating sidebar...');
        sleep(1);
        $this->sidebar($name);
        $this->line('
            #########   #########         #    #                 ####### ##        #
            #       #   #       #        # #    #               #   #    # #       #
            #       #   #       #       #   #    #             #    #    #  #      #
            #       #   #       #      #     #    #           #     #    #   #     #
            #########   #########     #########    #         #      #    #    #    #
            #           # #          #         #    #       #       #    #     #   #
            #           #  #        #           #    #     #        #    #      #  #
            #           #   #      #             #    #   #         #    #       # #
            #           #    #    #               #    # #          #    #        ##
            #           #     #  #                 #    #        ####### #         #');
    }

    /**
     * This function will get template content
     * @param  [string] $type 
     * @return [string]       
     */
    protected function getTemplate($type)
    {
        return file_get_contents(base_path("vendor/crud/src/templete/$type.temp"));
    }

    /**
     * This function will create model
     * @param  [string] $name 
     * @return [type]       
     */
    protected function model($name)
    {
        if (strpos($name, '/')) {
            list($folder,$file) = explode('/', $name);
            $modelTemplate = str_replace(
                ['{{modelName}}'],
                [$file],
                $this->getTemplate('model')
            );
            $path = app_path("/Models/{$file}.php");
            if (File::exists($path)) {
                $this->error("Model already exists.");
            }else{
                file_put_contents($path, $modelTemplate);
                $this->info('Model created successfully.');
            }
        }else{
            $modelTemplate = str_replace(
                ['{{modelName}}'],
                [$name],
                $this->getTemplate('model')
            );
            $path = app_path("/Models/{$name}.php");
            if (File::exists($path)) {
                $this->error("Model already exists.");
            }else{
                file_put_contents($path, $modelTemplate);
                $this->info('Model created successfully.');
            }
        }
    }

    /**
     * This function will create controller
     * @param  [string] $name 
     * @return [type]       
     */
    protected function controller($name)
    {
        if (strpos($name, '/')) {
            list($folder,$file) = explode('/', $name);
            $modelTemplate = str_replace(
                ['{{modelName}}','{{modelNameLower}}','{{Prefix}}','{{prefixLower}}'],
                [$file,strtolower($file),'\\'.$folder,strtolower($folder).'.'],
                $this->getTemplate('controller')
            );
            if(!file_exists($path = app_path("/Http/Controllers/{$folder}"))) {
                mkdir($path, 0777, true);
            }
            $path = app_path("/Http/Controllers/{$folder}/{$file}Controller.php");
            if (File::exists($path)) {
                $this->error("Controller already exists.");
            }else{
                file_put_contents($path, $modelTemplate);
                $this->info('Controller created successfully.');
            }
        }else{
            $controllerTemplate = str_replace(
                ['{{modelName}}','{{modelNameLower}}','{{Prefix}}','{{prefixLower}}'],
                [$name,strtolower($name),'',''],
                $this->getTemplate('controller')
            );
            $path = app_path("/Http/Controllers/{$name}Controller.php");
            if (File::exists($path)) {
                $this->error("Controller already exists.");
            }else{
                file_put_contents($path, $controllerTemplate);
                $this->info('Controller created successfully.');
            }
        }
    }

    /**
     * This function will create request
     * @param  [string] $name 
     * @param  [string] $option 
     * @return [type]       
     */
    protected function request($name,$option)
    {
        if (strpos($name, '/')) {
            list($folder,$file) = explode('/', $name);
            $requestTemplate = str_replace(
                ['{{modelName}}','{{Prefix}}'],
                [$file.$option,'\\'.$folder],
                $this->getTemplate('request')
            );
            if(!file_exists($path = app_path('/Http/Requests'))) {
                mkdir($path, 0777, true);
            }
            if(!file_exists($path = app_path("/Http/Requests/{$folder}"))) {
                mkdir($path, 0777, true);
            }
            $path = app_path("/Http/Requests/{$folder}/{$file}{$option}Request.php");
            if (File::exists($path)) {
                $this->error("Request already exists.");
            }else{
                file_put_contents($path, $requestTemplate);
                $this->info('Request created successfully.');
            }
        }else{
            $requestTemplate = str_replace(
                ['{{modelName}}','{{Prefix}}'],
                [$name.$option,''],
                $this->getTemplate('request')
            );
            if(!file_exists($path = app_path('/Http/Requests'))) {
                mkdir($path, 0777, true);
            }
            $path = app_path("/Http/Requests/{$name}{$option}Request.php");
            if (File::exists($path)) {
                $this->error("Request already exists.");
            }else{
                file_put_contents($path, $requestTemplate);
                $this->info('Request created successfully.');
            }
        }
    }

    /**
     * This function will create migration
     * @param  [string] $name 
     * @return [type]       
     */
    protected function migration($name)
    {
        $files = scandir(database_path("migrations/"));
        $files = array_diff(scandir(database_path("migrations/")), array('.', '..'));
        $fileName = $name;
        if (strpos($name, '/')) {
            $fileName = explode('/', $name)[1];
            $name = $fileName;
        }
        $status = true;
        foreach($files as $file){
          $content = file_get_contents(database_path("migrations/").$file);
          if (strpos($content,'Create'.Str::plural($name).'Table')) {
              $this->error("Cannot declare class Create".Str::plural($name)."Table, because the name is already in use.");
              $status = false;
              break;
          }
        }
        if ($status) {
            if (strpos($name, '/')) {
                list($folder,$file) = explode('/', $name);
                $slug = Str::snake($file);
                $slug = Str::plural($slug);
                $requestTemplate = str_replace(
                    ['{{modelName}}','{{modelPluralSlug}}'],
                    [Str::plural($file),$slug],
                    $this->getTemplate('migration')
                );
                $path = database_path("migrations/".date('Y_m_d_').time()."_create_".$slug."_table.php");
                file_put_contents($path, $requestTemplate);
                $this->info('Migration created successfully.');
            }else{
                $slug = Str::snake($name);
                $slug = Str::plural($slug);
                $requestTemplate = str_replace(
                    ['{{modelName}}','{{modelPluralSlug}}'],
                    [Str::plural($name),$slug],
                    $this->getTemplate('migration')
                );
                $path = database_path("migrations/".date('Y_m_d_').time()."_create_".$slug."_table.php");
                file_put_contents($path, $requestTemplate);
                $this->info('Migration created successfully.');
            }
        }
    }

    /**
     * This function will create ruute
     * @param  [string] $name 
     * @return [type]       
     */
    protected function route($name){
        if (strpos($name, '/')) {
            list($folder,$file) = explode('/', $name);
            $slug = strtolower($file);
            $folderSlug = strtolower($folder);
            $routeTemplate = str_replace(
                ['{{modelName}}','{{modelNameLower}}','{{namespace}}','{{modelNameRoute}}'],
                [$file,$folderSlug.'/'.$slug,",'namespace' => '$folder'",$folderSlug.'.'.$slug],
                $this->getTemplate('route')
            );
            File::append(base_path('routes/web.php'), PHP_EOL.$routeTemplate.PHP_EOL);
            $this->info('Route created successfully.');
        }else{
            $slug = strtolower($name);
            $routeTemplate = str_replace(
                ['{{modelName}}','{{modelNameLower}}','{{namespace}}','{{modelNameRoute}}'],
                [$name,$slug,'',$slug],
                $this->getTemplate('route')
            );
            File::append(base_path('routes/web.php'), PHP_EOL.$routeTemplate.PHP_EOL);
            $this->info('Route created successfully.');
        }
    }

    /**
     * This function will create lang
     * @param  [string] $name 
     * @return [type]       
     */
    protected function language($name)
    {
        if (strpos($name, '/')) {
            list($folder,$file) = explode('/', $name);
            $template = str_replace(
                ['{{modelName}}','{{modelNameLower}}'],
                [$file,strtolower($file)],
                $this->getTemplate('lang')
            );
            $modelNameLowerCase = strtolower($file);
        }else{
            $template = str_replace(
                ['{{modelName}}','{{modelNameLower}}'],
                [$name,strtolower($name)],
                $this->getTemplate('lang')
            );
            $modelNameLowerCase = strtolower($name);
        }
        $path = resource_path("/lang/en/{$modelNameLowerCase}.php");
        if (File::exists($path)) {
            $this->error("File already exists.");
        }else{
            file_put_contents(resource_path("/lang/en/{$modelNameLowerCase}.php"), $template);
            $this->info('Lang created successfully.');
        }
    }

    /**
     * This function will create script
     * @param  [string] $name 
     * @return [type]       
     */
    protected function script($name)
    {
        if(!file_exists($path = public_path('/js'))) {
            mkdir($path, 0777, true);
        }
        if (strpos($name, '/')) {
            list($folder,$file) = explode('/', $name);
            $slug = strtolower($file);
            $slug = Str::slug($slug);
            $prefixLower = strtolower($folder);
            $template = str_replace(
                ['{{modelName}}','{{modelNameLower}}','{{Prefix}}','{{modelPluralSlug}}'],
                [$file,strtolower($file),strtolower($folder).'/',$slug],
                $this->getTemplate('script')
            );
            $modelNameLowerCase = strtolower($file);
            if(!file_exists($path = public_path('/js/'.$prefixLower))) {
                mkdir($path, 0777, true);
            }
            $path = public_path("/js/{$prefixLower}/{$modelNameLowerCase}.js");
            if (File::exists($path)) {
                $this->error("File already exists.");
            }else{
                file_put_contents(public_path("/js/{$prefixLower}/{$modelNameLowerCase}.js"), $template);
                $this->info('Script created successfully.');
            }
        }else{
            $slug = strtolower($name);
            $slug = Str::plural($slug);
            $template = str_replace(
                ['{{modelName}}','{{modelNameLower}}','{{Prefix}}','{{modelPluralSlug}}'],
                [$name,strtolower($name),'',$slug],
                $this->getTemplate('script')
            );
            $modelNameLowerCase = strtolower($name);
            $path = public_path("/js/{$modelNameLowerCase}.js");
            if (File::exists($path)) {
                $this->error("File already exists.");
            }else{
                file_put_contents(public_path("/js/{$modelNameLowerCase}.js"), $template);
                $this->info('Script created successfully.');
            }
        }
    }
    /**
     * This function will create sidebar
     * @param  [string] $name
     * @return [type]      
     */
    protected function sidebar($name){
        if (strpos($name, '/')) {
            list($folder,$file) = explode('/', $name);
            $slug = strtolower($file);
            $folderSlug = strtolower($folder);
            $sidebarTemplate = str_replace(
                ['{{modelName}}','{{Route}}'],
                [$file,$folderSlug.'.'.$slug],
                $this->getTemplate('sidebar')
            );
            File::append(resource_path('views/layout/admin-sidebar.blade.php'), PHP_EOL.$sidebarTemplate.PHP_EOL);
            $this->info('Sidebar created successfully.');
        }else{
            $slug = strtolower($name);
            $sidebarTemplate = str_replace(
                ['{{modelName}}','{{Route}}'],
                [$name,$slug],
                $this->getTemplate('sidebar')
            );
            File::append(resource_path('views/layout/admin-sidebar.blade.php'), PHP_EOL.$sidebarTemplate.PHP_EOL);
            $this->info('Sidebar created successfully.');
        }
    }
}
